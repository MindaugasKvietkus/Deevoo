<?php
namespace AppBundle\Controller;

use AppBundle\Entity\ProjectImagesAdditionalVariables;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\LoginVariables;
use AppBundle\Form\LoginFormType;
use AppBundle\Entity\ProjectVariables;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use AppBundle\Entity\ProjectFromVariables;
use AppBundle\Entity\ImagesVariables;
use AppBundle\Entity\Email;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use AppBundle\Entity\Login;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Serializer\SerializerAwareTrait;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use AppBundle\Entity\CorrectUserDatabase;
use AppBundle\Form\AddUserFormType;
use Symfony\Component\Security\Core\Tests\Encoder\PasswordEncoder;


class MainController extends Controller
{
    /**
     * @Route("/", name="login")
     */
    public function index(Request $request){
    	
    	$login = new LoginVariables();
    	
    	$encoders = array(new XmlEncoder(), new JsonEncoder());
    	$normalizers = array(new ObjectNormalizer());
    	
    	$serializer = new Serializer($normalizers, $encoders);
    	
    	$form = $this->createForm('AppBundle\Form\LoginFormType', $login);
    	
    	if($request->getMethod() === 'POST'){
    		
    		$form = $form->handleRequest($request);
    		
    		if ($form->isValid()){
    			
    			$user = $this->getDoctrine()->getRepository("AppBundle:CorrectUserDatabase")->findOneBy(array(
    					'username' => $login->username,
    					'password' => $login->password,
    			));
    			
    			$json_user = $serializer->serialize($user, "json");
				$json_decode = json_decode($json_user, TRUE);
				
    			if (($login->username == $json_decode['username']) && ($login->password = $json_decode['password'])){
    				return $this->redirectToRoute ( 'home' );
    			}else{
    					echo "Not correct username or password";
    				}
    		}
    		
    	}
    	
    	
    	return $this->render('default/index.html.twig', array(
    			'form' => $form->createView(),
    	));
    	
    }
    
    /**
     * @Route ("/home", name="home")
     */
    
    public function home(){
    	
    	$project = $this->getDoctrine()
    	->getRepository("AppBundle:ProjectVariables")
    	->findAll();

    	return $this->render('default/home.html.twig', array(
    			'projects' => $project,
    	));
    }
    
    /**
     *@route("/addproject", name="addproject") 
     */
    
    public function addproject(Request $request){
    	
    	$addproject = new ProjectFromVariables();
    	 
    	$form = $this->createForm('AppBundle\Form\ProjectFormType', $addproject);
    	 
    	if($request->getMethod() === 'POST'){
    		
    		$form = $form->handleRequest($request);

    		if ($form->isValid()){


    			$project = new ProjectVariables();
    			$images_info = new ImagesVariables();
    			$em = $this->getDoctrine()->getManager();
    			
    			$project->disable = 0;
    			$project->project_name = $addproject->projectname;
    			
    			$em->persist($project);
    			$em->flush();

    			foreach($addproject->file as $file){
    				
    				$projectId = $project->getId();
    				
    				$photo = new ImagesVariables();
    				$photo->setProjectId($project);
    				$photo->setImagesName( $file->getClientOriginalName());
    				$photo->setImagesPath("/uploaded_images/".$project->id."/".$file->getClientOriginalName());
    				$project->file_info->add($photo);
    				$file->move($this->getParameter("temp_uploaded_images"), $file->getClientOriginalName());
    				//."/".$project->project_name."/"
    			}
    			
    			$em->persist($project);
    			$em->flush();
    			
    			$finder = new Finder();
    			$finder->files()->in($this->getParameter("temp_uploaded_images"));
    			
    			$change_path = $this->getParameter("uploaded_images")."/".$project->id."/";
    			
    			$fs = new Filesystem();
    			$fs->mirror($this->getParameter("temp_uploaded_images"), $change_path);
    			$fs->remove($this->getParameter("temp_uploaded_images"));
    			
    			
    		}    	
    	}    	 
    	 
    	return $this->render('default/addproject.html.twig', array(
    			'form' => $form->createView(),
    	));
    	
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function Edit($id){

        /**
         * @var $project ProjectVariables
         * @var $images ImagesVariables
         * */
    	$project = $this->getDoctrine()->getRepository("AppBundle:ProjectVariables")->find($id);

        $images = $this->getDoctrine()->getRepository("AppBundle:ImagesVariables")->findBy(array(
            'project_id' => $id
        ));

        $images = $project->getFileInfo();

        $addproject = new ProjectImagesAdditionalVariables();
        $addproject->project_name = $project->getProjectName();

        $array_names = array();
        $array_path = array();
        $array_id = array();

        foreach ($images as $image){
            array_push($array_names, $image->getImagesName());
            array_push($array_path, $image->getImagesPath());
            array_push($array_id, $image->getId());

        }
        $addproject->images_name = $array_names;
        $addproject->images_path = $array_path;
        $addproject->images_id = $array_id;

    	$form = $this->createForm('AppBundle\Form\ProjectImagesAdditionalForm', $addproject);


    	return $this->render('default/edit.html.twig', array(
    			'form' => $form->createView(),
    			'project' => $project,
    			'images' => $images,
                'id' => $array_id,
                'names' => $array_names,
                'project_id' => $id
    	));
    }
    
    /**
     * @Route("/disable/{id}")
     */
    
    public function setDisable($id){
    	
    	$em = $this->getDoctrine()->getManager();
    	$project = $em->getRepository("AppBundle:ProjectVariables")->find($id);
    	$project->setDisable(1);
    	$em->flush();
    	
    	return $this->redirectToRoute("home");
    	
    }
    
    /**
     * @Route("/enable/{id}")
     */
    
    public function setEnable($id){
    	
    	$em = $this->getDoctrine()->getManager();
    	$project = $em->getRepository("AppBundle:ProjectVariables")->find($id);
    	$project->setDisable(0);
    	$em->flush();
    	 
    	return $this->redirectToRoute("home");
    }
    
    /**
     * @Route("/view/{id}/{page}", name="projectView")
     */
    
    public function getView($id, $page = 1){
    	
    	$project = $this->getDoctrine()->getRepository("AppBundle:ProjectVariables")->find($id);
    	
    	if ($project->getDisable() == 0){
    		$images_count = $this->getDoctrine()->getRepository("AppBundle:ImagesVariables")->findBy(array(
    				'project_id' => $id
    		));
    		 
    		$limit = count($images_count);
    		$count = array();
    		 
    		for ($i = 1; $i<=$limit; $i++){
    			array_push($count, $i);
    		}
    		 
    		//print_r($count);
    		 
    		$images = $this->getDoctrine()->getRepository("AppBundle:ImagesVariables")->findBy(array(
    				'project_id' => $id
    		),array(), 1, $page);
    		 
    		//print_r($project);
    		 
    		return $this->render('default/view.html.twig', array(
    				'project' => $project,
    				'images' => $images,
    				'count' => $count
    		));
    	}else {
    		return $this->render('default/notfound.html.twig');
    	}
    	
    }
    
    /**
     * @Route("/send/{id}")
     */
    
    public function Send(Request $request, $id){
    	
    	$project = $this->getDoctrine()->getRepository("AppBundle:ProjectVariables")->find($id);
    	$send_email = new Email(); 
    	
    	$form = $this->createForm('AppBundle\Form\EmailFormType', $send_email);
    	
    	if($request->getMethod() === 'POST'){
    	
    		$form = $form->handleRequest($request);
    		 
    		if ($form->isValid()){
    			
    			$message = $send_email->message."\r\n\r\n<a href=\"localhost:8000/view/$id\">Peržiūra</a>";
    			$email = \Swift_Message::newInstance()
    			->setSubject("Send project")
    			->setFrom("mjndauqas@gmail.com", "Mindaugas Kvietkus")
    			->setTo($send_email->email)
    			->setBody($message, 'text/html');
    			
    			$sent = $this->get('mailer')->send($email);
    			
    		}
    	}
    	return $this->render('default/send.html.twig', array(
    			'form' => $form->createView()
    	));
    }
    
    /**
     * @Route("/delete/{id}", name="delete")
     */
    
    public function Delete($id){
    	
    	$em = $this->getDoctrine()->getManager();
    	$project = $em->getRepository("AppBundle:ProjectVariables")->find($id);
    	$em->remove($project);
    	$em->flush();
    	
    	return $this->redirectToRoute("home");
    }

    /**
     * @Route("/delete_images/{id}", name="delete_images")
     */

    public function DeleteImages($id){

        $em = $this->getDoctrine()->getManager();

        $image = $em->getRepository("AppBundle:ImagesVariables")->findOneBy(array(
            'id' => $id
        ));

        $project = $em->getRepository("AppBundle:ProjectVariables")->findOneBy(array(
            'id' => $image->getProjectId()
            )
        );

        $windows_path = str_replace('/', '\\', $image->getImagesPath());
        $path = $this->getParameter("web").$windows_path;
        $fs = new Filesystem();
        $fs->remove($path);
        $em->remove($image);
        $em->flush();

        return $this->redirectToRoute("edit", array(
            'id' => $project->getId()
        ));
    }

    /**
     * @Route ("/add_images/{id}", name="add_images")
     */
    /*
     * Neveikia
     */
    public function AddImage(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();

        $project = $em->getRepository("AppBundle:ProjectVariables")->findOneBy(array(
                'id' => $id
            )
        );

        $image = new ProjectImagesAdditionalVariables();

        print_r($image->add_file);

        foreach ($image->add_file as $file) {
            $photo = new ImagesVariables();
            $photo->setProjectId($project->id);
            $photo->setImagesName($file->getClientOriginalName());
            $photo->setImagesPath("/uploaded_images/" . $project->id . "/" . $file->getClientOriginalName());
            $project->file_info->add($image->add_file);
            $file->move($this->getParameter("temp_uploaded_images"), $file->getClientOriginalName());
            //."/".$project->project_name."/"
        }
        $em->persist($project);
        $em->flush();
        exit;
        $finder = new Finder();
        $finder->files()->in($this->getParameter("temp_uploaded_images"));

        $change_path = $this->getParameter("uploaded_images")."/".$project->id."/";

        $fs = new Filesystem();
        $fs->mirror($this->getParameter("temp_uploaded_images"), $change_path);
        $fs->remove($this->getParameter("temp_uploaded_images"));

    }

    /**
     * @Route ("/images_update/{id}", name="images_update")
     */

    public function ImagesUpdate($id){

        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository("AppBundle:ProjectVariables")->findOneBy(array(
            'id' => $id
        ));

        $image = $em->getRepository("AppBundle:ImagesVariables")->findBy(array(
            'project_id' => $id
        ));

        $image_form = new ProjectImagesAdditionalVariables();

        //print_r($image->getImagesPath());

        exit;
        print_r($project->getImagesName());
        print_r($project->getProjectId());
        print_r($project->getId());

        exit;
        $image_form->images_id = $project->getId();
        $project->setProjectId($project->getProjectId());
        $image_form->images_name = $project->getImagesName();
        $image_form->images_path = $project->getImagesPath();

        return $this->redirectToRoute("edit", array(
            ""
        ));


    }
    
    /**
     * @Route("/logout", name="logout")
     */
    
    public function Logout(){
    	/*
    	$session = new Session();
    	$username = $session->get("username");
    	
    	$session->remove("username");
    	
    	return $this->redirectToRoute("login", array(
    			'username' => $username
    	));
    	*/
    }
    
    /**
     * @Route("/test/{id}", name="test")
     */
    
    public function Test($id){
    	
    	$encoders = array(new XmlEncoder(), new JsonEncoder());
    	$normalizers = array(new ObjectNormalizer());
    	 
    	$serializer = new Serializer($normalizers, $encoders);
    	
    	/*
    	$user = $this->getDoctrine()->getRepository("AppBundle:Login")->findOneBy(array(
    			'username' => $login->username,
    			'password' => $login->password,
    	));
    	*/
    	
    	$project  = $this->getDoctrine()->getRepository("AppBundle:ProjectVariables")->findOneBy(array(
    			'id' => $id
    	));
    	
    	$project_id = $this->getDoctrine()->getRepository("AppBundle:ImagesVariables")->findBy(array(
    			'project_id' => $id
    	));
    	
    	
    	print_r($project);
    	
    	//$json_user = $serializer->serialize($project_id, "json");
    	//$json_decode = json_decode($json_user, TRUE);
    	
    	//print_r($json_user);
    	
    	return $this->render('default/test.html.twig', array(
    			'project_id' => $project_id
    	));
    }
    
    /**
     * @Route("/add_user", name="add_user")
     */
    
    public function Adduser(Request $request){
    	
    	$addUser = new CorrectUserDatabase();
    	
    	$form = $this->createForm("AppBundle\Form\AddUserFormType", $addUser);
    	
    	if($request->getMethod() === 'POST'){
    	
    		$form = $form->handleRequest($request);
    	
    		if ($form->isValid()){
    			
    			$password = $this->get("security.password_encoder")
    			->encodePassword($addUser, $addUser->getPassword());
    			$addUser->setPassword($password);
    			
    			$em = $this->getDoctrine()->getManager();
    			$em->persist($addUser);
    			$em->flush();
    			
    			return $this->redirectToRoute('home');
    			
    		}
    	}
    	
    	return $this->render("default/addUser.html.twig", array(
    			'form' =>$form->createView()
    	));
    }
}
