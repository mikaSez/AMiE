<?php

if(!empty($_POST['idNotification'])){
    $em = $this->getDoctrine()->getManager();

    $notification = $em->getRepository('AMiEHomeBundle:Notification')->findOneById($_POST['idNotification']);
    $notification->setVue(1);

    $em->persist($notification);
    $em->flush();

    echo '';
}

if(!empty($_POST['notifications'])){
    echo '';
}