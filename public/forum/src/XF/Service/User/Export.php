<?php

namespace XF\Service\User;

use XF\Mvc\Entity\Entity;
use XF\Service\AbstractXmlExport;
use XF\Util\Xml;

class Export extends AbstractXmlExport
{
	public function getRootName()
	{
		return 'data_portability_user';
	}

	public function getChildName()
	{
		return 'user';
	}

	protected function exportEntry(Entity $entity, \DOMElement $node)
	{
		$additionalAttributes = $this->getAdditionalAttributes($entity);
		foreach ($additionalAttributes AS $name => $value)
		{
			$node->setAttribute($name, $value);
		}

		$additionalChildNodes = $this->getAdditionalChildNodes($entity);
		foreach ($additionalChildNodes AS $name => $value)
		{
			$childNode = $node->ownerDocument->createElement($name);
			$newNode = Xml::createDomCdataSection($node->ownerDocument, $value);
			$childNode->appendChild($newNode);
			$node->appendChild($childNode);
		}

		$customFields = $this->getCustomFields($entity);
		$childNode = $node->ownerDocument->createElement('custom_fields');
		foreach ($customFields AS $name => $value)
		{
			$customFieldChild = $node->ownerDocument->createElement($name);
			if (is_array($value))
			{
				$customFieldChild->setAttribute('array', 'true');
				$newNode = Xml::createDomCdataSection($node->ownerDocument, json_encode($value));
			}
			else
			{
				$newNode = Xml::createDomCdataSection($node->ownerDocument, $value);
			}
			$customFieldChild->appendChild($newNode);
			$childNode->appendChild($customFieldChild);
		}
		$node->appendChild($childNode);
	}

	protected function getAttributes()
	{
		return [
			'username', 'email', 'timezone', 'gravatar'
		];
	}

	protected function getAdditionalAttributes(Entity $entity)
	{
		/** @var \XF\Entity\User $entity */
		return [
			'dob_day' => $entity->Profile->dob_day,
			'dob_month' => $entity->Profile->dob_month,
			'dob_year' => $entity->Profile->dob_year,
			'website' => $entity->Profile->website,
			'location' => $entity->Profile->location
		];
	}

	protected function getAdditionalChildNodes(Entity $entity)
	{
		/** @var \XF\Entity\User $entity */
		return [
			'signature' => $entity->Profile->signature,
			'about' => $entity->Profile->about
		];
	}

	protected function getCustomFields(Entity $entity)
	{
		/** @var \XF\Entity\User $entity */
		return $entity->Profile->custom_fields_;
	}
}