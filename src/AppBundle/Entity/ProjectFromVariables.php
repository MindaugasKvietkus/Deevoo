<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProjectFromVariables {
	
	public $projectname;
	
	/**
	 * @var UploadedFile
	 */
	
	public $file;

}