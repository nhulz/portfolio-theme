<?php

$context = array();
$context['message'] = 'Hello I am a message from PHP';

Timber::render('index.twig', $context);
