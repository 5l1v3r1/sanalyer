<?php

namespace XFMG\Entity;

use XF\Entity\QuotableInterface;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null comment_id
 * @property int content_id
 * @property string content_type
 * @property string message
 * @property int user_id
 * @property string username
 * @property int ip_id
 * @property int comment_date
 * @property string comment_state
 * @property int rating_id
 * @property int likes
 * @property array like_users
 * @property int warning_id
 * @property string warning_message
 * @property int last_edit_date
 * @property int last_edit_user_id
 * @property int edit_count
 * @property array|null embed_metadata
 *
 * GETTERS
 * @property Album|MediaItem Content
 * @property array|null GalleryMedia
 * @property array|null GalleryAlbums
 *
 * RELATIONS
 * @property \XFMG\Entity\Album Album
 * @property \XFMG\Entity\MediaItem Media
 * @property \XFMG\Entity\Rating Rating
 * @property \XF\Entity\ApprovalQueue ApprovalQueue
 * @property \XF\Entity\DeletionLog DeletionLog
 * @property \XF\Entity\LikedContent[] Likes
 * @property \XF\Entity\User User
 */
class Comment extends Entity implements QuotableInterface, \XF\BbCode\RenderableContentInterface
{
	public function canView(&$error = null)
	{
		$content = $this->Content;
		return ($content ? $content->canView($error) && $content->canViewComments($error) : false);
	}

	public function canEdit(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}

		$content = $this->Content;

		if ($content->hasPermission('editAnyComment'))
		{
			return true;
		}

		if ($this->user_id == $visitor->user_id && $content->hasPermission('editComment'))
		{
			$editLimit = $content->hasPermission('editOwnCommentTimeLimit');
			if ($editLimit != -1 && (!$editLimit || $this->comment_date < \XF::$time - 60 * $editLimit))
			{
				$error = \XF::phrase('xfmg_time_limit_to_edit_this_comment_x_minutes_has_expired', ['editLimit' => $editLimit]);
				return false;
			}

			return true;
		}

		return false;
	}

	public function canEditSilently(&$error = null)
	{
		$content = $this->Content;
		$visitor = \XF::visitor();
		if (!$visitor->user_id || !$content)
		{
			return false;
		}

		if ($content->hasPermission('editAnyComment'))
		{
			return true;
		}

		return false;
	}

	public function canViewHistory(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}

		if (!$this->app()->options()->editHistory['enabled'])
		{
			return false;
		}

		if ($this->Content->hasPermission('editAnyComment'))
		{
			return true;
		}

		return false;
	}

	public function canDelete($type = 'soft', &$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}

		$content = $this->Content;

		if ($type != 'soft' && !$content->hasPermission('hardDeleteAnyComment'))
		{
			return false;
		}

		if ($content->hasPermission('deleteAnyComment'))
		{
			return true;
		}

		if ($this->user_id == $visitor->user_id && $content->hasPermission('deleteComment'))
		{
			$editLimit = $content->hasPermission('editOwnCommentTimeLimit');
			if ($editLimit != -1 && (!$editLimit || $this->comment_date < \XF::$time - 60 * $editLimit))
			{
				$error = \XF::phrase('xfmg_time_limit_to_delete_this_comment_x_minutes_has_expired', ['editLimit' => $editLimit]);
				return false;
			}

			return true;
		}

		return false;
	}

	public function canUndelete(&$error = null)
	{
		$visitor = \XF::visitor();
		$content = $this->Content;
		if (!$visitor->user_id || !$content)
		{
			return false;
		}

		return $content->hasPermission('undeleteComment');
	}

	public function canApproveUnapprove(&$error = null)
	{
		if (!$this->Content)
		{
			return false;
		}

		return $this->Content->hasPermission('approveUnapproveComment');
	}

	public function canWarn(&$error = null)
	{
		$visitor = \XF::visitor();

		if ($this->warning_id
			|| !$this->user_id
			|| !$visitor->user_id
			|| $this->user_id == $visitor->user_id
			|| !$this->Content->hasPermission('warnComment')
		)
		{
			return false;
		}

		return ($this->User && $this->User->isWarnable());
	}

	public function canReport(&$error = null, \XF\Entity\User $asUser = null)
	{
		$asUser = $asUser ?: \XF::visitor();
		return $asUser->canReport($error);
	}

	public function canLike(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}

		if ($this->comment_state != 'visible')
		{
			return false;
		}

		if ($this->user_id == $visitor->user_id)
		{
			$error = \XF::phraseDeferred('liking_own_content_cheating');
			return false;
		}

		return $this->Content->hasPermission('likeComment');
	}

	public function canCleanSpam()
	{
		return (\XF::visitor()->canCleanSpam() && $this->User && $this->User->isPossibleSpammer());
	}

	public function canSendModeratorActionAlert()
	{
		$visitor = \XF::visitor();

		if (!$visitor->user_id || $visitor->user_id == $this->user_id)
		{
			return false;
		}

		if ($this->comment_state != 'visible')
		{
			return false;
		}

		return true;
	}

	public function canUseInlineModeration(&$error = null)
	{
		$visitor = \XF::visitor();
		return ($visitor->user_id && $this->Content->hasPermission('inlineModComment'));
	}

	public function isVisible()
	{
		if ($this->comment_state != 'visible')
		{
			return false;
		}

		$content = $this->Content;
		if (!$content)
		{
			return false;
		}
		else if ($content instanceof MediaItem)
		{
			return ($content->media_state == 'visible');
		}
		else if ($content instanceof Album)
		{
			return ($content->album_state == 'visible');
		}
		else
		{
			return true;
		}
	}

	public function isLiked()
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}

		return isset($this->Likes[$visitor->user_id]);
	}

	public function isIgnored()
	{
		return \XF::visitor()->isIgnoring($this->user_id);
	}

	public function isUnread()
	{
		$content = $this->Content;

		if (!$content)
		{
			return false;
		}

		$visitor = \XF::visitor();

		if (!isset($content->CommentRead[$visitor->user_id]))
		{
			return true;
		}

		$readDate = $content->getVisitorReadDate();
		if ($readDate === null)
		{
			return false;
		}

		return $readDate < $this->comment_date;
	}

	public function getQuoteWrapper($inner)
	{
		return '[QUOTE="'
			. ($this->User ? $this->User->username : $this->username)
			. ', xfmg-comment: ' . $this->comment_id
			. ($this->User ? ", member: $this->user_id" : '')
			. '"]'
			. $inner
			. "[/QUOTE]\n";
	}

	public function getBbCodeRenderOptions($context, $type)
	{
		return [
			'entity' => $this,
			'user' => $this->User
		];
	}

	/**
	 * @return Album|MediaItem
	 */
	public function getContent()
	{
		return ($this->content_type == 'xfmg_media' ? $this->Media : $this->Album);
	}

	/**
	 * @return array|null
	 */
	public function getGalleryMedia()
	{
		return isset($this->_getterCache['GalleryMedia']) ? $this->_getterCache['GalleryMedia'] : null;
	}

	/**
	 * @return array|null
	 */
	public function getGalleryAlbums()
	{
		return isset($this->_getterCache['GalleryAlbums']) ? $this->_getterCache['GalleryAlbums'] : null;
	}

	public function setGalleryMedia(array $galleryMedia)
	{
		$this->_getterCache['GalleryMedia'] = $galleryMedia;
	}

	public function setGalleryAlbums(array $galleryAlbums)
	{
		$this->_getterCache['GalleryAlbums'] = $galleryAlbums;
	}

	public function softDelete($reason = '', \XF\Entity\User $byUser = null)
	{
		$byUser = $byUser ?: \XF::visitor();

		if ($this->comment_state == 'deleted')
		{
			return false;
		}

		$this->comment_state = 'deleted';

		/** @var \XF\Entity\DeletionLog $deletionLog */
		$deletionLog = $this->getRelationOrDefault('DeletionLog');
		$deletionLog->setFromUser($byUser);
		$deletionLog->delete_reason = $reason;

		$this->save();

		return true;
	}

	protected function _postSave()
	{
		$visibilityChange = $this->isStateChanged('comment_state', 'visible');
		$approvalChange = $this->isStateChanged('comment_state', 'moderated');
		$deletionChange = $this->isStateChanged('comment_state', 'deleted');

		if ($this->isUpdate())
		{
			if ($visibilityChange == 'enter')
			{
				if ($approvalChange)
				{
					$this->submitHamData();
				}
			}
			else if ($visibilityChange == 'leave')
			{
				/** @var \XF\Repository\UserAlert $alertRepo */
				$alertRepo = $this->repository('XF:UserAlert');
				$alertRepo->fastDeleteAlertsForContent('xfmg_comment', $this->comment_id);
			}

			if ($deletionChange == 'leave' && $this->DeletionLog)
			{
				$this->DeletionLog->delete();
			}

			if ($approvalChange == 'leave' && $this->ApprovalQueue)
			{
				$this->ApprovalQueue->delete();
			}
		}

		if ($approvalChange == 'enter')
		{
			$approvalQueue = $this->getRelationOrDefault('ApprovalQueue', false);
			$approvalQueue->content_date = $this->comment_date;
			$approvalQueue->save();
		}
		else if ($deletionChange == 'enter' && !$this->DeletionLog)
		{
			$delLog = $this->getRelationOrDefault('DeletionLog', false);
			$delLog->setFromVisitor();
			$delLog->save();
		}

		$this->updateContentRecord();

		if ($this->isUpdate() && $this->getOption('log_moderator'))
		{
			$this->app()->logger()->logModeratorChanges('xfmg_comment', $this);
		}
	}

	protected function updateContentRecord()
	{
		$content = $this->Content;
		$category = $content->Category;

		$visibilityChange = $this->isStateChanged('comment_state', 'visible');
		if ($visibilityChange == 'enter' && $content)
		{
			$content->commentAdded($this);
			$content->save();

			if ($category)
			{
				$category->commentAdded($this);
				$category->save();
			}
		}
		else if ($visibilityChange == 'leave' && $content)
		{
			$content->commentRemoved($this);
			$content->save();

			if ($category)
			{
				$category->commentRemoved($this);
				$category->save();
			}
		}
	}

	protected function submitHamData()
	{
		/** @var \XF\Spam\ContentChecker $submitter */
		$submitter = $this->app()->container('spam.contentHamSubmitter');
		$submitter->submitHam('xfmg_comment', $this->comment_id);
	}

	protected function _postDelete()
	{
		if ($this->Content && $this->comment_state == 'visible')
		{
			$this->Content->commentRemoved($this);
			$this->Content->save();
		}

		if ($this->comment_state == 'deleted' && $this->DeletionLog)
		{
			$this->DeletionLog->delete();
		}

		if ($this->comment_state == 'moderated' && $this->ApprovalQueue)
		{
			$this->ApprovalQueue->delete();
		}

		if ($this->getOption('log_moderator'))
		{
			$this->app()->logger()->logModeratorAction('xfmg_comment', $this, 'delete_hard');
		}

		$this->db()->delete('xf_edit_history', 'content_type = ? AND content_id = ?', ['xfmg_comment', $this->comment_id]);

		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->repository('XF:UserAlert');
		$alertRepo->fastDeleteAlertsForContent('xfmg_comment', $this->comment_id);
	}

	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_mg_comment';
		$structure->shortName = 'XFMG:Comment';
		$structure->contentType = 'xfmg_comment';
		$structure->primaryKey = 'comment_id';
		$structure->columns = [
			'comment_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'content_id' => ['type' => self::UINT, 'required' => true],
			'content_type' => ['type' => self::STR, 'required' => true,
				'allowedValues' => ['xfmg_media', 'xfmg_album']
			],
			'message' => ['type' => self::STR,
				'required' => 'please_enter_valid_message'
			],
			'user_id' => ['type' => self::UINT, 'default' => 0],
			'username' => ['type' => self::STR, 'maxLength' => 50],
			'ip_id' => ['type' => self::UINT, 'default' => 0],
			'comment_date' => ['type' => self::UINT, 'default' => \XF::$time],
			'comment_state' => ['type' => self::STR, 'default' => 'visible',
				'allowedValues' => ['visible', 'moderated', 'deleted']
			],
			'rating_id' => ['type' => self::UINT, 'default' => 0],
			'likes' => ['type' => self::UINT],
			'like_users' => ['type' => self::SERIALIZED_ARRAY],
			'warning_id' => ['type' => self::UINT, 'default' => 0],
			'warning_message' => ['type' => self::STR, 'default' => '', 'maxLength' => 255],
			'last_edit_date' => ['type' => self::UINT, 'default' => 0],
			'last_edit_user_id' => ['type' => self::UINT, 'default' => 0],
			'edit_count' => ['type' => self::UINT, 'forced' => true, 'default' => 0],
			'embed_metadata' => ['type' => self::JSON_ARRAY, 'nullable' => true, 'default' => null]
		];
		$structure->behaviors = [
			'XF:Likeable' => ['stateField' => 'comment_state'],
			'XF:NewsFeedPublishable' => [
				'usernameField' => 'username',
				'dateField' => 'comment_date'
			],
			'XF:Indexable' => [
				'checkForUpdates' => ['message', 'user_id', 'content_id', 'comment_date', 'comment_state']
			]
		];
		$structure->getters = [
			'Content' => true,
			'GalleryMedia' => true,
			'GalleryAlbums' => true
		];
		$structure->relations = [
			'Album' => [
				'entity' => 'XFMG:Album',
				'type' => self::TO_ONE,
				'conditions' => [
					['$content_type', '=', 'xfmg_album'],
					['album_id', '=', '$content_id']
				]
			],
			'Media' => [
				'entity' => 'XFMG:MediaItem',
				'type' => self::TO_ONE,
				'conditions' => [
					['$content_type', '=', 'xfmg_media'],
					['media_id', '=', '$content_id']
				]
			],
			'Rating' => [
				'entity' => 'XFMG:Rating',
				'type' => self::TO_ONE,
				'conditions' => 'rating_id',
				'primary' => true
			],
			'ApprovalQueue' => [
				'entity' => 'XF:ApprovalQueue',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'xfmg_comment'],
					['content_id', '=', '$comment_id']
				],
				'primary' => true
			],
			'DeletionLog' => [
				'entity' => 'XF:DeletionLog',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'xfmg_comment'],
					['content_id', '=', '$comment_id']
				],
				'primary' => true
			],
			'Likes' => [
				'entity' => 'XF:LikedContent',
				'type' => self::TO_MANY,
				'conditions' => [
					['content_type', '=', 'xfmg_comment'],
					['content_id', '=', '$comment_id']
				],
				'key' => 'like_user_id',
				'order' => 'like_date'
			],
			'User' => [
				'entity' => 'XF:User',
				'type' => self::TO_ONE,
				'conditions' => 'user_id',
				'primary' => true
			]
		];
		$structure->defaultWith = [
			'Album', 'Media'
		];
		$structure->options = [
			'log_moderator' => true
		];

		return $structure;
	}
}