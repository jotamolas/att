<?php

namespace att\ctrlaccBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use att\ctrlaccBundle\Entity\Device;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * @Route("{mode}/ctrlacc/device", requirements={"mode":"frontend|backend"})
 */
class DeviceController extends Controller {

    /**
     * @Route("/", name="ctrlacc_index")
     * @param Request $request
     */
    public function indexAction(Request $request) {

        return $this->render('ctrlaccBundle:Default:index.html.twig');
    }

    /**
     * @Route("/list", name="ctrlacc_device_list")
     */
    public function listDeviceAction() {

        $devices = $this->getDoctrine()->getRepository('ctrlaccBundle:Device')->findAll();
        $types = $this->getDoctrine()->getRepository('syncBundle:Atexternsystemtype')->findAll();
        return $this->render('ctrlaccBundle:Default:device.list.html.twig', [
                    'devices' => $devices,
                    'types' => $types]);
    }

    /**
     * @Route("/ping/{device}", name="ctrlacc_device_ping")
     * @param Request $request
     */
    public function pingToDevice(Request $request, Device $device) {
        
        if($device->getSystem()->getIp() === 'localhost' or $device->getSystem()->getIp() === '127.0.0.1'){
            $process = new Process("netstat -tulpn | grep :".$device->getSystem()->getPort());
        }
        else{
            $process = new Process("telnet ".$device->getSystem()->getIp()." ".$device->getSystem()->getPort());            
        }
        $process->run();
        
        dump($process->getOutput());
        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        
    }
    
    /**
     * @Route("/device/{device}/config/metadata", name="ctrlacc_device_metadata_config")
     */
    public function metadataConfig(Request $request, Device $device){
        
        
        $metadata = $this->getDoctrine()->getRepository('ctrlaccBundle:DeviceMetadataConfiguration')->findOneByDevice($device);
        $form = $this->createForm('att\ctrlaccBundle\Form\DeviceConfigureType', $metadata);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('ctrlacc_device_list',[
                'mode' =>  $this->get('security.token_storage')->getToken()->getProviderKey()
            ]);
        }
        
        return $this->render('ctrlaccBundle:Default:device.metadata.form.html.twig',[
            'form' => $form->createView(),
            'device' => $device
        ]);
        
    }

}
