<?php $this->layout = 'InvoiceComponent.default'; // $data ?>
<html>
    <head>
        <style>
        </style>
    </head>
    <body>
        <h2>Prontostoreus</h2>
        <h3><?= $data['subject']; ?></h3>
        <hr><br>
        <div>
            <div>
                <h4 style="text-align: center;">Thank you for your application!</h4>
                <p>This invoice confirms we are processing your application and summarises its particulars. 
                If you need to contact the company, you can find their details below.</p>
            </div>
            <br>
            <div id="application-parties-data">
                <div id="customer-details" style="float: left; width: 25%;">
                    <p id="customer-name"><strong>
                        <?= 
                            $data['application']['customer']['title'] . ". " . 
                            $data['application']['customer']['firstname'] . " " . 
                            $data['application']['customer']['surname']; 
                        ?>
                    </strong></p>
                    <p id="customer-email">
                        <?= 
                            $data['application']['customer']['email'];
                        ?>
                    </p>
                    <p id="customer-telephone">
                        <?= 
                            $data['application']['customer']['telephone'];
                        ?>
                    </p>
                    <p id="customer-address-line-one">
                        <?= 
                            $data['application']['customer']['addresses'][0]['line_one'];
                        ?>
                    </p>
                    <p id="customer-address-line-two">
                        <?= 
                            $data['application']['customer']['addresses'][0]['line_two'];
                        ?>
                    </p>
                    <p id="customer-address-town">
                        <?= 
                            $data['application']['customer']['addresses'][0]['town'];
                        ?>
                    </p>
                    <p id="customer-address-county">
                        <?= 
                            $data['application']['customer']['addresses'][0]['county'];
                        ?>
                    </p>
                    <p id="customer-address-postcode">
                        <?= 
                            str_replace("-", " ", $data['application']['customer']['addresses'][0]['postcode']);
                        ?>
                    </p>
                </div>
                <div id="company-details" style="float: right; width: 25%;">
                    <p id="company-name"><strong>
                        <?= 
                            $data['application']['company']['name'];
                        ?>
                    </strong></p>
                    <p id="company-email">
                        <?= 
                            $data['application']['company']['email'];
                        ?>
                    </p>
                    <p id="company-telephone">
                        <?= 
                            $data['application']['company']['telephone'];
                        ?>
                    </p>
                    <p id="company-address-line-one">
                        <?= 
                            $data['application']['company']['addresses'][0]['line_one'];
                        ?>
                    </p>
                    <p id="company-address-line-two">
                        <?= 
                            $data['application']['company']['addresses'][0]['line_two'];
                        ?>
                    </p>
                    <p id="company-address-town">
                        <?= 
                            $data['application']['company']['addresses'][0]['town'];
                        ?>
                    </p>
                    <p id="company-address-county">
                        <?= 
                            $data['application']['company']['addresses'][0]['county'];
                        ?>
                    </p>
                    <p id="company-address-postcode">
                        <?= 
                            str_replace("-", " ", $data['application']['company']['addresses'][0]['postcode']);
                        ?>
                    </p>
                </div>
            </div>
            <hr><br>
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th scope="col">Ref</th>
                        <th scope="col">Submission Date</th>
                        <th scope="col">Invoice Issued</th>
                        <th scope="col">Payment Due</th>
                        <th scope="col">Method</th>
                        <th scope="col">Terms and Conditions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <th scope="row" id="invoice-ref">
                        <?= 
                            $data['reference'];
                        ?>
                    </th>
                        <td id="invoice-submission-date">
                            <?php 
                                $date = explode(",", $data['application']['created']);
                                echo str_replace('-','/', date("d-m-Y", strtotime($date[0])));
                            ?>
                        </td>
                        <td id="invoice-issued-date">
                            <?php 
                                $date = explode(",", $data['issued']);
                                echo str_replace('-','/', date("d-m-Y", strtotime($date[0])));
                            ?>
                        </td>
                        <td id="invoice-payment-due-date">
                            <?php 
                                $date = explode(",", $data['due']);
                                echo str_replace('-','/', date("d-m-Y", strtotime($date[0])));
                            ?>
                        </td>
                        <td id="invoice-method">
                            <?= 
                                ($data['application']['delivery']) ? "Delivery" : "Collection";
                            ?>
                        </td>
                        <td id="invoice-terms">
                            <?= 
                                ($data['confirmations']['accepted']) ? "Accepted" : "Declined";
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>

            <br><br>
            
            <table style="width: 100%;">
                <thead>
                    <tr>
                    <th scope="col">Line</th>
                        <th>Room</th>
                        <th>Furnishing</th>
                        <th>Item Cost</th>
                        <th>Quantity</th>
                        <th>Line Total</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                      for ($lineNumber = 0; $lineNumber < sizeof($data['application']['application_lines']); $lineNumber++) { 
                          $row = $lineNumber + 1; ?>
                         <tr>
                            <th scope="row"><?= $row ?></th>
                            <td><?= $data['application']['application_lines'][$lineNumber]['furnishing']['room']['description']; ?></td>
                            <td>
                                <?= 
                                    $data['application']['application_lines'][$lineNumber]['furnishing']['description'] . " (" . 
                                    $data['application']['application_lines'][$lineNumber]['furnishing']['size'] . "m" . "<sup>2</sup>" . "/" . 
                                    $data['application']['application_lines'][$lineNumber]['furnishing']['weight'] . "kg)"; 
                                ?>
                            </td>
                            <td>£
                                <?php
                                    $lineCost = (float)$data['application']['application_lines'][$lineNumber]['line_cost'];
                                    $quantity = (float)$data['application']['application_lines'][$lineNumber]['quantity'];
                                    $itemCost = $lineCost / $quantity;
                                    echo number_format($itemCost, 2, '.', ''); 
                                ?>
                            </td>
                            <td><?= $data['application']['application_lines'][$lineNumber]['quantity']; ?></td>
                            <td>£<?= number_format((float)$data['application']['application_lines'][$lineNumber]['line_cost'], 2, '.', ''); ?></td>
                         </tr> 
                <?php } ?>
                </tbody>
            </table>

            <br><br>
            <p style="float: right; width: 25%;"><strong>Total: £<?= number_format((float)$data['application']['total_cost'], 2, '.', ''); ?></strong></p>
        </div>
    </body>
</html>


