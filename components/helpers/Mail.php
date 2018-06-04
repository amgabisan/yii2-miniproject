<?php

namespace app\components\helpers;

use Yii;

class Mail
{
    public function sendMail($body, $username)
    {
        $mailBody =
            '<html>
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
                    <title>AMG</title>
                    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
                    <style type="text/css">
                        html {
                            font-family: "Montserrat", sans-serif;
                        }
                        body {
                            padding: 3%;
                        }
                        #mainTable {
                            background-color: #F0EBEF;
                            border-radius: 10px;
                        }
                        #mainTable td {
                            padding-left: 15px;
                            padding-right: 15px;
                        }
                        #logoContainer {
                            background: -webkit-linear-gradient(82deg, rgb(204, 43, 94), rgb(117, 58, 136));
                            background: linear-gradient(82deg, rgb(204, 43, 94), rgb(117, 58, 136));
                        }
                        .text-center {
                            text-align: center;
                        }
                        .text-right {
                            text-align: right;
                        }
                        .btn {
                          font-family: Arial;
                          color: #ffffff;
                          font-size: 24px;
                          padding: 20px;
                          background: #337ab7;
                          text-decoration: none;
                          border: none;
                        }
                        .btn:hover {
                          background: #286090;
                          text-decoration: none;
                        }
                    </style>
                </head>
                <body>
                    <table width="75%" border="0" cellpadding="0" cellspacing="0" align="center" id="mainTable">
                        <tbody>
                            <tr id="logoContainer">
                                <td class="text-center">
                                    <img src="https://c120.pcloud.com/dpZxj6PUJZiCXDiJZ41nUZZUFgjU7Z3VZZsuXZXZJGP3097rsPbI2h79T7RNxyCp8d9k/th-6707983031-200x150.png" alt="amg-logo" border="0">
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 10px;">
                                    <h2>Hello '.$username.', </h2>
                                </td>
                            </tr>
                            <tr>
                                 <td style="padding-bottom: 10px; line-height: 1.25; text-align: justify;">
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla metus nibh, pretium eu quam et, aliquam laoreet nisi. Aenean mauris magna, vehicula id sapien vitae, semper mattis magna. Phasellus sed posuere mi, eu vestibulum sem. Quisque rutrum nunc sit amet dictum molestie.
                                </td>
                            </tr>
                            <tr>
                               '.$body.'
                            <tr>
                                 <td style="padding-top: 10px; padding-bottom: 10px">
                                    Thank you and Have a great day!
                                </td>
                            </tr>
                            <tr>
                                 <td style="padding-top: 10px; padding-bottom: 10px" class="text-right">
                                    Sincerely yours, <br><h2>AMG Team </h2>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </body>
            </html>';

        return $mailBody;
    }
}

/*
    <tr>
        <td style="padding-top: 10px; text-align: center">
            <button type="button" class="btn">Confirm Account</button>
        </td>
    </tr>
    <tr>
        <td style="padding-top: 10px; text-align: center">
            <button type="button" class="btn">Reset Password</button>
        </td>
    </tr>
    <tr>
        <td style="padding-top: 10px; ">
            Thank you for registering and since you registered using our OAuth Service, here is your generated password: <h2 class="text-center">Password</h2>
        </td>
    </tr>
*/
?>
