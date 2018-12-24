<?php

namespace XFMG\EmbedData;

use Symfony\Component\DomCrawler\Crawler;

class BaseData
{
	/**
	 * @var \XF\App
	 */
	protected $app;

	public function __construct(\XF\App $app)
	{
		$this->app = $app;
	}

	/**
	 * Given a valid usable media site URL provided by a user, and the site ID and media ID from our match
	 * attempt to fetch and return a temp path pointing to whichever thumbnail we want to use or null if fetching
	 * fails for some reason or otherwise not supported.
	 *
	 * The default code here is sufficient in most cases, but could be overridden if, for example, an API could get the info.
	 *
	 * @param $url
	 * @param $bbCodeMediaSiteId
	 * @param $siteMediaId
	 *
	 * @return null|string
	 */
	public function getTempThumbnailPath($url, $bbCodeMediaSiteId, $siteMediaId)
	{
		$reader = $this->app->http()->reader();

		$response = $reader->getUntrusted($url);
		if (!$response || $response->getStatusCode() != 200)
		{
			return null;
		}

		$crawler = new Crawler($response->getBody()->getContents());
		$ogImage = $crawler->filter('meta[property="og:image"]')->first()->extract('content');
		$ogImage = reset($ogImage);

		if (!$ogImage)
		{
			return null;
		}

		$response = $reader->getUntrusted($ogImage);
		if (!$response || $response->getStatusCode() != 200)
		{
			return null;
		}

		return $this->createTempThumbnailFromBody($response->getBody());
	}

	protected function createTempThumbnailFromBody($body)
	{
		$tempFile = \XF\Util\File::getTempFile();
		$fp = @fopen($tempFile, 'w');

		if (!$fp)
		{
			return null;
		}

		fwrite($fp, $body);
		fclose($fp);

		return $tempFile;
	}


	/**
	 * Given a valid usable media site URL provided by a user, and the site ID and media ID from our match
	 * attempt tp fetch and return an array containing the default title and description or an empty array if fetching
	 * fails for some reason or otherwise not supported.
	 *
	 * The default code here is sufficient in most cases, but could be overridden if, for example, an API could get the info.
	 *
	 * @param $url
	 * @param $bbCodeMediaSiteId
	 * @param $siteMediaId
	 *
	 * @return array
	 */
	public function getTitleAndDescription($url, $bbCodeMediaSiteId, $siteMediaId)
	{
		$response = $this->app->http()->reader()->getUntrusted(
			$url,
			[
				'time' => 5,
				'bytes' => 1.5 * 1024 * 1024
			]
		);
		if (!$response || $response->getStatusCode() != 200)
		{
			return [];
		}

		$charset = null;

		$contentType = $response->getHeader('Content-type');
		if ($contentType)
		{
			$parts = explode(';', $contentType, 2);

			$type = trim($parts[0]);
			if ($type != 'text/html')
			{
				return [];
			}

			if (isset($parts[1]) && preg_match('/charset=([-a-z0-9_]+)/i', trim($parts[1]), $match))
			{
				$charset = $match[1];
			}
		}

		$body = $response->getBody()->read(50 * 1024);

		$output = [
			'title' => '',
			'description' => ''
		];

		if (preg_match('#<meta[^>]+property="(og:|twitter:)title"[^>]*content="([^">]+)"#siU', $body, $match))
		{
			$output['title'] = isset($match[2]) ? $match[2] : '';
		}
		if (!$output['title'] && preg_match('#<title[^>]*>(.*)</title>#siU', $body, $match))
		{
			$output['title'] = $match[1];
		}
		if (preg_match('#<[\s]*meta[\s]*(name|property)="(og:|twitter:|)description"?[\s]*content="?([^>"]*)"?[\s]*[\/]?[\s]*>#simU', $body, $match))
		{
			$output['description'] = $match[3];
		}

		if (!$output['title'] && !$output['description'])
		{
			return $output;
		}

		if (!$charset)
		{
			preg_match('/charset=([^;"\\s]+|"[^;"]+")/i', $body, $contentTypeMatch);

			if (isset($contentTypeMatch[1]))
			{
				$charset = trim($contentTypeMatch[1], " \t\n\r\0\x0B\"");
			}

			if (!$charset)
			{
				$charset = 'windows-1252';
			}
		}

		// Clean the string and convert charset where applicable.
		return array_map(function($string) use ($charset)
		{
			if (!$string)
			{
				return '';
			}
			$string = \XF::cleanString($string);

			// note: assumes charset is ascii compatible
			if (preg_match('/[\x80-\xff]/', $string))
			{
				$newString = false;
				if (function_exists('iconv'))
				{
					$newString = @iconv($charset, 'utf-8//IGNORE', $string);
				}
				if (!$newString && function_exists('mb_convert_encoding'))
				{
					$newString = @mb_convert_encoding($string, 'utf-8', $charset);
				}
				$string = ($newString ? $newString : preg_replace('/[\x80-\xff]/', '', $string));

				$string = utf8_unhtml($string, true);
				$string = preg_replace('/[\xF0-\xF7].../', '', $string);
				$string = preg_replace('/[\xF8-\xFB]..../', '', $string);
			}

			$string = html_entity_decode($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
			$string = utf8_unhtml($string);
			$string = \XF::cleanString($string);

			if (!strlen($string))
			{
				return '';
			}
			
			return $string;
		}, $output);
	}
}