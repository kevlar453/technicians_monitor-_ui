<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        <?= $title_web; ?>
    </title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets_style/assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets_style/assets/bower_components/font-awesome/css/font-awesome.min.css">
    <style>
        /* Custom Styles */
        body {
            max-width: 8.5in;
            margin: 0.01in;
            font-size:9px;
        }
        table {
        	page-break-inside: avoid;
            width: 100%;
            border-collapse: collapse;
        }

        tr, th, td {
          padding:1px 1px 0 0;
            text-align: left;
            line-height: 50%;
            padding-top: 6px;
            border-spacing: 0px;
        }

        .detail td{
          border: 1px solid #000;
          padding-left: 5px;
        }

        .img-tt img{
          width:60%;
          height: auto;
        }

        *{margin:0;padding:0;}

    </style>

  </head>

  <body>
<!--
  <center><button id="generate-pdf" class="button is-primary">Download PDF</button></center>
-->
    <div class="table-container" id="acreport">
      <table cellspacing="0" cellpadding="0" class="">
        <tr style="text-align:center;" class="is-light">
          <td style="font-size:12px;border-top: 2px solid #000;border-left: 2px solid #000;width:35%;white-space:nowrap;text-align:center;">
            <p>
              <strong><?php echo $treport[0]->rep_logo1 ?></strong>
            </p>
          </td>
          <td colspan="2" style="font-size:14px;border-top: 2px solid #000;width:30%;font-weight:bolder;text-align:center;">
            <p>
              <strong>AIR CONDITIONER SYSTEM</strong>
            </p>
            <p>
              <strong>SERVICE REPORT</strong>
            </p>
          </td>
          <td style="font-size:12px;border-top: 2px solid #000;border-right: 2px solid #000;width:35%;white-space:nowrap;text-align:center;">
            <p>
              <strong><?php echo $treport[0]->rep_logo2 ?></strong>
            </p>
          </td>
        </tr>
        <tr>
          <td colspan="4" style="border-left: 2px solid #000;border-right: 2px solid #000;border-bottom:0;">

            <table cellspacing="0" cellpadding="0" class="" style="margin-bottom:1px;">
              <tr style="border: 1px solid #000;">
                <td style="border: 1px solid #000;width:20%;">
                  <p>
                    <strong>Date</strong>
                  </p>
                </td>
                <td style="border: 1px solid #000;width:30%;">
                  <p><?php echo $treport[0]->rep_tgl ?></p>
                </td>
                <td style="border: 1px solid #000;width:20%;">
                  <p>
                    <strong>Label Number</strong>
                  </p>
                </td>
                <td style="border: 1px solid #000;width:30%;">
                  <p><?php echo $treport[0]->rep_label ?></p>
                </td>
              </tr>
              <tr style="border: 1px solid #000;">
                <td style="border: 1px solid #000;">
                  <p>
                    <strong>Customer</strong>
                  </p>
                </td>
                <td style="border: 1px solid #000;">
                  <p><?php echo $treport[0]->rep_nmclient ?></p>
                </td>
                <td style="border: 1px solid #000;">
                  <p>
                    <strong>Model/Version</strong>
                  </p>
                </td>
                <td style="border: 1px solid #000;">
                  <p><?php echo $treport[0]->rep_model ?></p>
                </td>
              </tr>
              <tr style="border: 1px solid #000;">
                <td style="border: 1px solid #000;">
                  <p>
                    <strong>Periode / Info</strong>
                  </p>
                </td>
                <td style="border: 1px solid #000;">
                  <p><?php echo $treport[0]->rep_periode ?></p>
                </td>
                <td style="border: 1px solid #000;">
                  <p>
                    <strong>Serial Number</strong>
                  </p>
                </td>
                <td style="border: 1px solid #000;">
                  <p><?php echo $treport[0]->rep_serial ?></p>
                </td>
              </tr>
            </table>

            <?php
$arjob = explode(',',$treport[0]->rep_job);

// Search for the element "✔" in the array
$vjob = array_search("&#10003;", $arjob);

$vjob1 = $vjob == 0?'<i class="fa fa-check" style="font-size:8px"></i>':'<i class="fa fa-close" style="font-size:8px"></i>';
$vjob2 = $vjob == 1?'<i class="fa fa-check" style="font-size:8px"></i>':'<i class="fa fa-close" style="font-size:8px"></i>';
$vjob3 = $vjob == 2?'<i class="fa fa-check" style="font-size:8px"></i>':'<i class="fa fa-close" style="font-size:8px"></i>';
$vjob4 = $vjob == 3?'<i class="fa fa-check" style="font-size:8px"></i>':'<i class="fa fa-close" style="font-size:8px"></i>';
            ?>



            <table cellspacing="0" cellpadding="0" class="" style="margin:0;">
              <tr>
                <td style="border: 1px solid #000;width:15%;">
                  <p style="font-weight: bold;"> Job Description </p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
                <td style="border: 1px solid #000;width:16%;">
                  <p style="font-weight: bold;"> Maintenance </p>
                </td>
                <td style="border: 1px solid #000;text-align:center;width:3%;">
                  <p><?php echo $vjob1; ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
                <td style="font-weight: bold;border: 1px solid #000;width:16%;">
                  <p> Survey </p>
                </td>
                <td style="border: 1px solid #000;text-align:center;width:3%;">
                  <p><?php echo $vjob2; ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
                <td style="font-weight: bold;border: 1px solid #000;width:16%;">
                  <p> Repair </p>
                </td>
                <td style="border: 1px solid #000;text-align:center;width:3%;">
                  <p><?php echo $vjob3; ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
                <td style="font-weight: bold;border: 1px solid #000;width:20%;">
                  <p> Test &amp; Commissioning </p>
                </td>
                <td style="border: 1px solid #000;text-align:center;width:3%;">
                  <p><?php echo $vjob4; ?></p>
                </td>
              </tr>
            </table>
            <p></p>
          </td>
        </tr>
        <tr>
          <td colspan="4" style="border-right: 2px solid #000;border-left: 2px solid #000;border-bottom:0;">
            <table cellspacing="0" cellpadding="0" class="detail" style="border: 1px solid #000;">
              <tr>
                <td style="text-align:center;">
                  <p>
                    <strong>No</strong>
                  </p>
                </td>
                <td style="text-align:center;">
                  <p>
                    <strong>Item Checked</strong>
                  </p>
                </td>
                <td style="text-align:center;">
                  <p>
                    <strong>Unit</strong>
                  </p>
                </td>
                <td style="text-align:center;">
                  <p>
                    <strong>Range</strong>
                  </p>
                </td>
                <td colspan="3" style="text-align:center;">
                  <p>
                    <strong>Actual Checked</strong>
                  </p>
                </td>
                <td style="text-align:center;">
                  <p>
                    <strong>Remarks</strong>
                  </p>
                </td>
              </tr>
              <tr>
                <td rowspan="5">
                  <p> 1 </p>
                </td>
                <td colspan="7" style="font-weight: bold;">
                  <p> Electrical Power Supply </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Voltage line to Neutral Groud </p>
                </td>
                <td style="text-align:center;">
                  <p> Volt </p>
                </td>
                <td style="text-align:center;">
                  <p> 220 ± 10% </p>
                </td>
                <td>
                  <p> L1: <?php echo explode(',',$trep01[0]->rep1v01)[0] ?></p>
                </td>
                <td>
                  <p> L2: <?php echo explode(',',$trep01[0]->rep1v01)[1] ?></p>
                </td>
                <td>
                  <p> L3: <?php echo explode(',',$trep01[0]->rep1v01)[2] ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Voltage line to line </p>
                </td>
                <td>
                  <p style="text-align:center;"> Volt </p>
                </td>
                <td style="text-align:center;">
                  <p> 380 ± 10% </p>
                </td>
                <td>
                  <p> L1: <?php echo explode(',',$trep01[0]->rep1v02)[0] ?></p>
                </td>
                <td>
                  <p> L2: <?php echo explode(',',$trep01[0]->rep1v02)[1] ?></p>
                </td>
                <td>
                  <p> L3: <?php echo explode(',',$trep01[0]->rep1v02)[2] ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Frequency </p>
                </td>
                <td style="text-align:center;">
                  <p> Hz </p>
                </td>
                <td style="text-align:center;">
                  <p> 50 ± 10% </p>
                </td>
                <td colspan="3" style="text-align:center;">
                  <p> <?php echo $trep01[0]->rep1v03 ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Cable Connection </p>
                </td>
                <td style="text-align:center;">
                  <p> N/A </p>
                </td>
                <td style="text-align:center;">
                  <p> Ok or Not Ok </p>
                </td>
                <td colspan="3" style="text-align:center;">
                  <p> <?php echo $trep01[0]->rep1v04 ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td rowspan="7">
                  <p> 2 </p>
                </td>
                <td colspan="3" style="font-weight: bold;">
                  <p> Blower Section </p>
                </td>
                <td style="text-align:center;">
                  <p> Fan 1 </p>
                </td>
                <td style="text-align:center;">
                  <p> Fan 2 </p>
                </td>
                <td style="text-align:center;">
                  <p> Fan 3 </p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Motor Mounting </p>
                </td>
                <td style="text-align:center;">
                  <p> N/A </p>
                </td>
                <td style="text-align:center;">
                  <p> Ok or Not Ok </p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo explode(',',$trep02[0]->rep2v01)[0] ?></p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo explode(',',$trep02[0]->rep2v01)[1] ?></p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo explode(',',$trep02[0]->rep2v01)[2] ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Fan belt </p>
                </td>
                <td style="text-align:center;">
                  <p> N/A </p>
                </td>
                <td style="text-align:center;">
                  <p> Ok or Not Ok </p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo explode(',',$trep02[0]->rep2v02)[0] ?></p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo explode(',',$trep02[0]->rep2v02)[1] ?></p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo explode(',',$trep02[0]->rep2v02)[2] ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Fan safety Switch </p>
                </td>
                <td style="text-align:center;">
                  <p> N/A </p>
                </td>
                <td style="text-align:center;">
                  <p> Ok or Not Ok </p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo explode(',',$trep02[0]->rep2v03)[0] ?></p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo explode(',',$trep02[0]->rep2v03)[1] ?></p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo explode(',',$trep02[0]->rep2v03)[2] ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Bearing </p>
                </td>
                <td style="text-align:center;">
                  <p> N/A </p>
                </td>
                <td style="text-align:center;">
                  <p> Ok or Not Ok </p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo explode(',',$trep02[0]->rep2v04)[0] ?></p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo explode(',',$trep02[0]->rep2v04)[1] ?></p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo explode(',',$trep02[0]->rep2v04)[2] ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Pulley </p>
                </td>
                <td style="text-align:center;">
                  <p> N/A </p>
                </td>
                <td style="text-align:center;">
                  <p> Ok or Not Ok </p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo explode(',',$trep02[0]->rep2v05)[0] ?></p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo explode(',',$trep02[0]->rep2v05)[1] ?></p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo explode(',',$trep02[0]->rep2v05)[2] ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Current </p>
                </td>
                <td colspan="2" style="text-align:center;">
                  <p> Amp </p>
                </td>
                <td style="text-align:center;">
                  <p> <?php $rep2 = explode(',',$trep02[0]->rep2v06); echo $rep2[0].' | '.$rep2[1].' | '.$rep2[2] ?> </p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo $rep2[3].' | '.$rep2[4].' | '.$rep2[5] ?> </p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo $rep2[6].' | '.$rep2[7].' | '.$rep2[8] ?> </p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td rowspan="7">
                  <p> 3 </p>
                </td>
                <td colspan="3" style="font-weight: bold;">
                  <p> Compressor Section </p>
                </td>
                <td style="text-align:center;">
                  <p> Comp 1 </p>
                </td>
                <td style="text-align:center;">
                  <p> Comp 2 </p>
                </td>
                <td style="text-align:center;">
                  <p> Comp 3 </p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Suction Pressure </p>
                </td>
                <td style="text-align:center;">
                  <p> PsiG </p>
                </td>
                <td style="text-align:center;">
                  <p> 55 - 90 </p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo explode(',',$trep03[0]->rep3v01)[0] ?></p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo explode(',',$trep03[0]->rep3v01)[1] ?></p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo explode(',',$trep03[0]->rep3v01)[2] ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Discharge Pressure </p>
                </td>
                <td style="text-align:center;">
                  <p> PsiG </p>
                </td>
                <td style="text-align:center;">
                  <p> 200 - 300 </p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo explode(',',$trep03[0]->rep3v02)[0] ?></p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo explode(',',$trep03[0]->rep3v02)[1] ?></p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo explode(',',$trep03[0]->rep3v02)[2] ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Oil Level </p>
                </td>
                <td style="text-align:center;">
                  <p> N/A </p>
                </td>
                <td style="text-align:center;">
                  <p> Ok or Not Ok </p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo explode(',',$trep03[0]->rep3v03)[0] ?></p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo explode(',',$trep03[0]->rep3v03)[1] ?></p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo explode(',',$trep03[0]->rep3v03)[2] ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Leakage </p>
                </td>
                <td style="text-align:center;">
                  <p> N/A </p>
                </td>
                <td style="text-align:center;">
                  <p> Ok or Not Ok </p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo explode(',',$trep03[0]->rep3v04)[0] ?></p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo explode(',',$trep03[0]->rep3v04)[1] ?></p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo explode(',',$trep03[0]->rep3v04)[2] ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Sight Glass </p>
                </td>
                <td style="text-align:center;">
                  <p> N/A </p>
                </td>
                <td style="text-align:center;">
                  <p> Ok or Not Ok </p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo explode(',',$trep03[0]->rep3v05)[0] ?></p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo explode(',',$trep03[0]->rep3v05)[1] ?></p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo explode(',',$trep03[0]->rep3v05)[2] ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Check Current Compressor </p>
                </td>
                <td colspan="2" style="text-align:center;">
                  <p> Amp </p>
                </td>
                <td style="text-align:center;">
                  <p> <?php $rep3 = explode(',',$trep03[0]->rep3v06); echo $rep3[0].' | '.$rep3[1].' | '.$rep3[2] ?> </p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo $rep3[3].' | '.$rep3[4].' | '.$rep3[5] ?> </p>
                </td>
                <td style="text-align:center;">
                  <p> <?php echo $rep3[6].' | '.$rep3[7].' | '.$rep3[8] ?> </p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td rowspan="2">
                  <p> 4 </p>
                </td>
                <td colspan="7" style="font-weight: bold;">
                  <p> Reheat </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Check current element operation </p>
                </td>
                <td colspan="2" style="text-align:center;">
                  <p> Amp </p>
                </td>
                <td>
                  <p> L1: <?php $rep4 = explode(',',$trep04[0]->rep4v01); echo $rep4[0] ?></p>
                </td>
                <td>
                  <p> L2: <?php echo $rep4[1] ?></p>
                </td>
                <td>
                  <p> L3: <?php echo $rep4[2] ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td rowspan="4">
                  <p> 5 </p>
                </td>
                <td colspan="7" style="font-weight: bold;">
                  <p> Humidifier </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Make up water valve </p>
                </td>
                <td style="text-align:center;">
                  <p> N/A </p>
                </td>
                <td style="text-align:center;">
                  <p> Ok or Not Ok </p>
                </td>
                <td colspan="3" style="text-align:center;">
                  <p> <?php echo $trep05[0]->rep5v01 ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Water level sensor </p>
                </td>
                <td style="text-align:center;">
                  <p> N/A </p>
                </td>
                <td style="text-align:center;">
                  <p> Ok or Not Ok </p>
                </td>
                <td colspan="3" style="text-align:center;">
                  <p> <?php echo $trep05[0]->rep5v02 ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Infra Red/Stream Current </p>
                </td>
                <td colspan="2" style="text-align:center;">
                  <p> Amp </p>
                </td>
                <td>
                  <p> L1: <?php $rep5 = explode(',',$trep05[0]->rep5v03); echo $rep5[0] ?></p>
                </td>
                <td>
                  <p> L2: <?php echo $rep5[1] ?></p>
                </td>
                <td>
                  <p> L3: <?php echo $rep5[2] ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td rowspan="5">
                  <p> 6 </p>
                </td>
                <td colspan="7" style="font-weight: bold;">
                  <p> Air Cooled Condensor </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Check Current Operation </p>
                </td>
                <td colspan="2" style="text-align:center;">
                  <p> Amp </p>
                </td>
                <td>
                  <p> L1: <?php $rep6 = explode(',',$trep06[0]->rep6v01); echo $rep6[0] ?></p>
                </td>
                <td>
                  <p> L2: <?php echo $rep6[1] ?></p>
                </td>
                <td>
                  <p> L3: <?php echo $rep6[2] ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Check Condensor Coil </p>
                </td>
                <td style="text-align:center;">
                  <p> N/A </p>
                </td>
                <td style="text-align:center;">
                  <p> Ok or Not Ok </p>
                </td>
                <td colspan="3" style="text-align:center;">
                  <p> <?php echo $trep06[0]->rep6v02 ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Bearing </p>
                </td>
                <td style="text-align:center;">
                  <p> N/A </p>
                </td>
                <td style="text-align:center;">
                  <p> Ok or Not Ok </p>
                </td>
                <td colspan="3" style="text-align:center;">
                  <p> <?php echo $trep06[0]->rep6v03 ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Condition arround area Cond. </p>
                </td>
                <td style="text-align:center;">
                  <p> N/A </p>
                </td>
                <td style="text-align:center;">
                  <p> Ok or Not Ok </p>
                </td>
                <td colspan="3" style="text-align:center;">
                  <p> <?php echo $trep06[0]->rep6v04 ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td rowspan="6">
                  <p> 7 </p>
                </td>
                <td colspan="7" style="font-weight: bold;">
                  <p> General Function </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Cooling Test </p>
                </td>
                <td style="text-align:center;">
                  <p> N/A </p>
                </td>
                <td style="text-align:center;">
                  <p> Ok or Not Ok </p>
                </td>
                <td colspan="3" style="text-align:center;">
                  <p> <?php echo $trep07[0]->rep7v01 ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Heating Test </p>
                </td>
                <td style="text-align:center;">
                  <p> N/A </p>
                </td>
                <td style="text-align:center;">
                  <p> Ok or Not Ok </p>
                </td>
                <td colspan="3" style="text-align:center;">
                  <p> <?php echo $trep07[0]->rep7v02 ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Humidification Test </p>
                </td>
                <td style="text-align:center;">
                  <p> N/A </p>
                </td>
                <td style="text-align:center;">
                  <p> Ok or Not Ok </p>
                </td>
                <td colspan="3" style="text-align:center;">
                  <p> <?php echo $trep07[0]->rep7v03 ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Dehumidification Test </p>
                </td>
                <td style="text-align:center;">
                  <p> N/A </p>
                </td>
                <td style="text-align:center;">
                  <p> Ok or Not Ok </p>
                </td>
                <td colspan="3" style="text-align:center;">
                  <p> <?php echo $trep07[0]->rep7v04 ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Alarm Test </p>
                </td>
                <td style="text-align:center;">
                  <p> N/A </p>
                </td>
                <td style="text-align:center;">
                  <p> Ok or Not Ok </p>
                </td>
                <td colspan="3" style="text-align:center;">
                  <p> <?php echo $trep07[0]->rep7v05 ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td rowspan="4">
                  <p> 8 </p>
                </td>
                <td colspan="7" style="font-weight: bold;">
                  <p> Cleaning </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Cleaning unit air conditioner </p>
                </td>
                <td style="text-align:center;">
                  <p> N/A </p>
                </td>
                <td style="text-align:center;">
                  <p> Ok or Not Ok </p>
                </td>
                <td colspan="3" style="text-align:center;">
                  <p> <?php echo $trep08[0]->rep8v01 ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Cleaning air filter </p>
                </td>
                <td style="text-align:center;">
                  <p> N/A </p>
                </td>
                <td style="text-align:center;">
                  <p> Ok or Not Ok </p>
                </td>
                <td colspan="3" style="text-align:center;">
                  <p> <?php echo $trep08[0]->rep8v02 ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Cleaning drainage </p>
                </td>
                <td style="text-align:center;">
                  <p> N/A </p>
                </td>
                <td style="text-align:center;">
                  <p> Ok or Not Ok </p>
                </td>
                <td colspan="3" style="text-align:center;">
                  <p> <?php echo $trep08[0]->rep8v03 ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td rowspan="3">
                  <p> 9 </p>
                </td>
                <td style="font-weight: bold;">
                  <p> Room Condition </p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
                <td colspan="2">
                  <p> Set Point </p>
                </td>
                <td colspan="2" style="text-align:center;">
                  <p> Actual Return </p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Temperature </p>
                </td>
                <td style="text-align:center;">
                  <p> °C </p>
                </td>
                <td colspan="2" style="text-align:center;">
                  <p> <?php echo explode(',',$trep09[0]->rep9v01)[0] ?></p>
                </td>
                <td colspan="2" style="text-align:center;">
                  <p> <?php echo explode(',',$trep09[0]->rep9v01)[1] ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p> Humidity </p>
                </td>
                <td style="text-align:center;">
                  <p> %RH </p>
                </td>
                <td colspan="2" style="text-align:center;">
                  <p> <?php echo explode(',',$trep09[0]->rep9v02)[0] ?></p>
                </td>
                <td colspan="2" style="text-align:center;">
                  <p> <?php echo explode(',',$trep09[0]->rep9v01)[1] ?></p>
                </td>
                <td>
                  <p> &#xa0; </p>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td colspan="4" style="border-left: 2px solid #000;border-right: 2px solid #000;border-bottom:0;">
            <table cellspacing="0" cellpadding="0" class="" style="margin:0;">
              <tr>
                <td colspan="2" style="border: 1px solid #000;width:50%;">
                  <p>
                    <strong>Status</strong>
                  </p>
                </td>
                <td style="border-bottom:0;botder-top:0;width:1%;">
                  <p>
                    <strong>&#xa0;</strong>
                  </p>
                </td>
                <td rowspan="4" style="border: 1px solid #000;">
                  <p> Notes: </p>
                  <p> <?php echo $treport[0]->rep_desc ?></p>
                </td>
              </tr>

              <?php
  $arsts = explode(',',$treport[0]->rep_status);

  // Search for the element "✔" in the array
  $vsts = array_search("&#10003;", $arsts);

  $vsts1 = $vsts == 0?'<i class="fa fa-check" style="font-size:8px"></i>':'<i class="fa fa-close" style="font-size:8px"></i>';
  $vsts2 = $vsts == 1?'<i class="fa fa-check" style="font-size:8px"></i>':'<i class="fa fa-close" style="font-size:8px"></i>';
  $vsts3 = $vsts == 2?'<i class="fa fa-check" style="font-size:8px"></i>':'<i class="fa fa-close" style="font-size:8px"></i>';
              ?>


              <tr>
                <td style="border: 1px solid #000;">
                  <p>
                    <strong>Normal</strong>
                  </p>
                </td>
                <td style="text-align:center;border: 1px solid #000;">
                  <p> <?php echo $vsts1 ?></p>
                </td>
                <td style="border:0;">
                  <p>
                    <strong>&#xa0;</strong>
                  </p>
                </td>
              </tr>
              <tr>
                <td style="border: 1px solid #000;">
                  <p>
                    <strong>Failure</strong>
                  </p>
                </td>
                <td style="text-align:center;border: 1px solid #000;">
                  <p> <?php echo $vsts2 ?></p>
                </td>
                <td style="border:0;">
                  <p>
                    <strong>&#xa0;</strong>
                  </p>
                </td>
              </tr>
              <tr>
                <td style="border: 1px solid #000;">
                  <p>
                    <strong>Repair</strong>
                  </p>
                </td>
                <td style="text-align:center;border: 1px solid #000;">
                  <p> <?php echo $vsts3 ?></p>
                </td>
                <td style="border:0;">
                  <p>
                    <strong>&#xa0;</strong>
                  </p>
                </td>
              </tr>
            </table>
            <p></p>
          </td>
        </tr>
        <tr>
          <td colspan="2" style="border-left: 2px solid #000;border-bottom:0;width:50%;">
              <strong>Customer,</strong>
            <div style="width:50%;height:auto;margin:0;padding:0;" class="img-tt"><?php echo $treport[0]->rep_ttclient ?></div>
          </td>
          <td colspan="2" style="border-right: 2px solid #000;border-bottom:0;">
              <strong>Engineer/Technician,</strong>
            <div style="width:50%;height:auto;margin:0;padding:0;" class="img-tt"><?php echo $treport[0]->rep_tttek ?></div>
          </td>
        </tr>
        <tr>
          <td colspan="2" style="border-left: 2px solid #000;border-bottom: 2px solid #000;width:50%;">
            <p>
              <strong>Name:</strong><?php echo $treport[0]->rep_nmpic ?>
            </p>
          </td>
          <td colspan="2" style="border-right: 2px solid #000;border-bottom: 2px solid #000;">
            <p>
              <strong>Name: </strong><?php echo $treport[0]->rep_nmtek ?>
            </p>
          </td>
        </tr>
      </table>
    </div>

    <script src="<?php echo base_url(); ?>assets_style/assets/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets_style/assets/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets_style/assets/bower_components/bootstrap/dist/js/bootstrap.js"></script>
    <script src="https://github.com/pipwerks/PDFObject/blob/master/pdfobject.min.js"></script>

    <!-- AdminLTE App -->
    <script src="<?php echo base_url(); ?>assets_style/assets/dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?php echo base_url(); ?>assets_style/assets/dist/js/demo.js"></script>

</body>

</html>
