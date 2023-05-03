<!DOCTYPE HTML>
<html>
    <head>
        <style type="text/css">
            @page {  
                margin-top: 80px !important;
                margin-bottom: 5px !important;
                margin-left: 40px !important;
                footer: page-footer !important;;
                /*float: left;*/
            }
            .bodyBody {
                margin-top: 5px !important;
                padding-top: 10px;
                font-size: 1em;
            }
            .divHeader {
                text-align: center;
                /*margin-top: 100px !important;*/
            }
            .divContents{
                padding-left: 40px !important;
                margin-top: 28px !important;
            }   
            .sub{
                display: inline-block !important;
            }
            .details{
                padding-bottom: 15px !important;
            }
            table td, table th {
                padding: 5px; /* cellpadding */
                /*padding: 5px; /* cellpadding Laptop*/ 
                text-align:justify !important;
            }
            table th{
                text-align: center !important;
            }
            .page-break {
              page-break-after: always !important;
              /*margin-top: 70px !important;*/
            }
        </style>
        <!-- <link href="http://localhost/digitalrti/public/la-assets/css/bootstrap.css" rel="stylesheet" type="text/css" /> -->
    </head>
    <body class="bodyBody">
        <div class="divContents">
            
            <div class="divHeader">
                <h3>Sub Department Count</h3> 
            </div>
            <br><br>
            <table style="width:100%;" cellspacing="1" border="1" class="page-break">
              <tr>
                <th valign="top"><b>Ministry/State Ministry/Provincial Council</b></th>
                <th valign="top"><b>Sub Department Count</b></th>
              </tr>      
            <?php 
                $d = $pdfData['rpt_data'];
                // dd($d);
                if(sizeof($d)>0){
                for ($i=0; $i <sizeof($d) ; $i++) { 
                if($d[$i]->sub!=null){  
                    $splitSub   = explode(',',$d[$i]->sub);
                    $size       = sizeof($splitSub);
                }else{
                    $size       = '-';
                }
             ?>
               <tr>
                    <td><?php echo $pdfData['rpt_data'][$i]->lev2;?></td>
                    <td><?php echo $size;?></td>
               </tr>
              <?php }?>
            </table>
            <htmlpagefooter name="page-footer">
                <p style="text-align: right; font-style: italic; color:gray; font-size: 11px;">Prepared By :<?php echo $pdfData['prepared']->name;?> ,Created By : PUBAD - IT Division ,Created Date :<?php echo date("d-m-Y");?></p>
            </htmlpagefooter>
            <?php }else{echo "No data to display.";}?>
        </div>
    </body>
</html>