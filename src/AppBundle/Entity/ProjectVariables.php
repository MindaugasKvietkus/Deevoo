<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class ProjectVariables {
	
	public $id;
	
	public $project_name;
	
	public $disable = NULL;
	
	public $file_info;
	
	public function __construct(){
		
		$this->file_info = new ArrayCollection();
	}
	
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set projectName
     *
     * @param string $projectName
     *
     * @return ProjectVariables
     */
    public function setProjectName($projectName)
    {
        $this->project_name = $projectName;

        return $this;
    }

    /**
     * Get projectName
     *
     * @return string
     */
    public function getProjectName()
    {
        return $this->project_name;
    }

    /**
     * Set disable
     *
     * @param integer $disable
     *
     * @return ProjectVariables
     */
    public function setDisable($disable)
    {
        $this->disable = $disable;

        return $this;
    }

    /**
     * Get disable
     *
     * @return integer
     */
    public function getDisable()
    {
        return $this->disable;
    }

    /**
     * Add fileInfo
     *
     * @param \AppBundle\Entity\ImagesVariables $fileInfo
     *
     * @return ProjectVariables
     */
    public function addFileInfo(\AppBundle\Entity\ImagesVariables $fileInfo)
    {
        $this->file_info[] = $fileInfo;

        return $this;
    }

    /**
     * Remove fileInfo
     *
     * @param \AppBundle\Entity\ImagesVariables $fileInfo
     */
    public function removeFileInfo(\AppBundle\Entity\ImagesVariables $fileInfo)
    {
        $this->file_info->removeElement($fileInfo);
    }

    /**
     * Get fileInfo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFileInfo()
    {
        return $this->file_info;
    }
}
