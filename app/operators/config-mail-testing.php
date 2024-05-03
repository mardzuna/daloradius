<?php
/*
 *********************************************************************************************************
 * daloRADIUS - RADIUS Web Platform
 * Copyright (C) 2007 - Liran Tal <liran@enginx.com> All Rights Reserved.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 *********************************************************************************************************
 *
 * Authors:    Liran Tal <liran@enginx.com>
 *             Filippo Lauria <filippo.lauria@iit.cnr.it>
 *
 *********************************************************************************************************
 */

    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

    //~ include('library/check_operator_perm.php');
    include_once('../common/includes/config_read.php');
    include_once("lang/main.php");
    include("../common/includes/validation.php");
    include("../common/includes/layout.php");

    // init logging variables
    $log = "visited page: ";
    $logAction = "";
    $logDebugSQL = "";

    $param_label = array(
                            'recipient_email_address' => "Recipient's email address",
                            'recipient_name' => "Recipient's name",
                            
                        );

    $invalid_input = array();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (array_key_exists('csrf_token', $_POST) && isset($_POST['csrf_token']) && dalo_check_csrf_token($_POST['csrf_token'])) {

            if ($configValues['CONFIG_MAIL_ENABLED'] === "yes") {
                
                // validate recipient's email
                if (!(
                        array_key_exists('recipient_email_address', $_POST) &&
                        !empty(trim($_POST['recipient_email_address'])) &&
                        filter_var(trim($_POST['recipient_email_address']), FILTER_VALIDATE_EMAIL)
                   )) {

                    $invalid_input['recipient_email_address'] = $param_label['recipient_email_address'];
                }
                
                // validate recipient's name
                if (!(
                        array_key_exists('recipient_name', $_POST) &&
                        !empty(trim($_POST['recipient_name'])) &&
                        preg_match(RECIPIENT_NAME_REGEX, trim($_POST['recipient_name']))
                   )) {
                    $invalid_input['recipient_name'] = $param_label['recipient_name'];
                }
            
                if (count($invalid_input) > 0) {
                    $failureMsg = sprintf("Invalid input: [%s]", implode(", ", array_values($invalid_input)));
                    $logAction .= "$failureMsg on page: ";
                } else {
                    
                    include "../common/includes/notifications.php";
                    
                    // Sample recipient information
                    $recipient_email_address = trim($_POST['recipient_email_address']);
                    $recipient_name = trim($_POST['recipient_name']);

                    // Sample email content
                    $subject = 'Test Email';
                    $body = 'This is a test email. If you received this, your SMTP mailer is working fine.';

                    // Call the send_email function
                    list($success, $message) = send_email($configValues, $recipient_email_address, $recipient_name, $subject, $body);

                    // Check the result
                    if ($success) {
                        $successMsg = $message;
                    } else {
                        $failureMsg = $message;
                    }
                }
                
            } else {
                // error
                $failureMsg = "Mail sender is not enabled";
                $logAction .= "$failureMsg on page: ";
            }
            
        } else {
            // csrf
            $failureMsg = "CSRF token error";
            $logAction .= "$failureMsg on page: ";
        }
    }

    // print HTML prologue
    $title = t('Intro','configmail.php');
    $help = t('helpPage','configmail');

    print_html_prologue($title, $langCode);

    print_title_and_help($title, $help);

    include_once('include/management/actionMessages.php');

    $fieldset0_descriptor = array(
                                    "title" => t('title','Settings')
                                 );

    $input_descriptors0 = array();



    $input_descriptors0[] = array(
                                        "type" => "email",
                                        "caption" => $param_label['recipient_email_address'],
                                        "name" => 'recipient_email_address',
                                        "value" => ((isset($recipient_email_address)) ? $recipient_email_address : ""),
                                        "required" => true,
                                     );

    $input_descriptors0[] = array(
                                        "type" => "text",
                                        "caption" => $param_label['recipient_name'],
                                        "name" => 'recipient_name',
                                        "value" => ((isset($recipient_name)) ? $recipient_name : ""),
                                        "pattern" => trim(RECIPIENT_NAME_REGEX, "/"),
                                        "title" => "allowed letters, numbers and spaces",
                                     );


    $input_descriptors0[] = array(
                                    "name" => "csrf_token",
                                    "type" => "hidden",
                                    "value" => dalo_csrf_token(),
                                 );

    $input_descriptors0[] = array(
                                    'type' => 'submit',
                                    'name' => 'submit',
                                    'value' => t('buttons','apply')
                                 );

    open_form();

    // open 0-th fieldset
    open_fieldset($fieldset0_descriptor);

    foreach ($input_descriptors0 as $input_descriptor) {
        print_form_component($input_descriptor);
    }

    close_fieldset();

    close_form();

    include('include/config/logging.php');
    print_footer_and_html_epilogue();

?>
