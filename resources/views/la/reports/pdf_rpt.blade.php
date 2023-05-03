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
            <?php 
                $d = $pdfData['rpt_data']['data'];
                if(sizeof($d)>0){
                for ($i=0; $i <sizeof($d) ; $i++) { 
                $splitDesig = explode(',',$d[$i]->desig);
                $splitTotal = explode(',',$d[$i]->total);
             ?>
            
            <div class="divHeader">
                <h3><?php echo $d[$i]->name;?></h3> 
                <h4><?php echo $pdfData['category'];?></h4> 
            </div>
            <br><br>
            <table style="width:100%;" cellspacing="1" border="1" class="page-break">
              <tr>
                <th valign="top" rowspan="2"><b>Designation</b></th>
                <th valign="top" colspan="17"><b>Year</b></th>
                <th valign="top" rowspan="2"><b>Total</b></th>
              </tr>
              <tr>
                <th valign="top"><b>2005</b></th>
                <th valign="top"><b>2006</b></th>
                <th valign="top"><b>2007</b></th>
                <th valign="top"><b>2008</b></th>
                <th valign="top"><b>2009</b></th>
                <th valign="top"><b>2010</b></th>
                <th valign="top"><b>2011</b></th>
                <th valign="top"><b>2012</b></th>
                <th valign="top"><b>2013</b></th>
                <th valign="top"><b>2014</b></th>
                <th valign="top"><b>2015</b></th>
                <th valign="top"><b>2016</b></th>
                <th valign="top"><b>2017</b></th>
                <th valign="top"><b>2018</b></th>
                <th valign="top"><b>2019</b></th>
                <th valign="top"><b>2020</b></th>
                <th valign="top"><b>2021</b></th>
              </tr>
              <?php 
                    $split2005  = explode(',',$d[$i]->d2005);
                    $split2006  = explode(',',$d[$i]->d2006);
                    $split2007  = explode(',',$d[$i]->d2007);
                    $split2008  = explode(',',$d[$i]->d2008);
                    $split2009  = explode(',',$d[$i]->d2009);
                    $split2010  = explode(',',$d[$i]->d2010);
                    $split2011  = explode(',',$d[$i]->d2011);
                    $split2012  = explode(',',$d[$i]->d2012);
                    $split2013  = explode(',',$d[$i]->d2013);
                    $split2014  = explode(',',$d[$i]->d2014);
                    $split2015  = explode(',',$d[$i]->d2015);
                    $split2016  = explode(',',$d[$i]->d2016);
                    $split2017  = explode(',',$d[$i]->d2017);
                    $split2018  = explode(',',$d[$i]->d2018);
                    $split2019  = explode(',',$d[$i]->d2019);
                    $split2020  = explode(',',$d[$i]->d2020);
                    $split2021  = explode(',',$d[$i]->d2021);                   
                    for ($a=0; $a <sizeof($splitDesig) ; $a++) {?> 
                    <tr>
                        <td><?php echo $splitDesig[$a];?></td>
                        <td align ="right"><?php if($split2005[$a]==""){echo '-';}else{echo $split2005[$a];}?></td>
                        <td align ="right"><?php if($split2006[$a]==""){echo '-';}else{echo $split2006[$a];}?></td>
                        <td align ="right"><?php if($split2007[$a]==""){echo '-';}else{echo $split2007[$a];}?></td>
                        <td align ="right"><?php if($split2008[$a]==""){echo '-';}else{echo $split2008[$a];}?></td>
                        <td align ="right"><?php if($split2009[$a]==""){echo '-';}else{echo $split2009[$a];}?></td>
                        <td align ="right"><?php if($split2010[$a]==""){echo '-';}else{echo $split2010[$a];}?></td>
                        <td align ="right"><?php if($split2011[$a]==""){echo '-';}else{echo $split2011[$a];}?></td>
                        <td align ="right"><?php if($split2012[$a]==""){echo '-';}else{echo $split2012[$a];}?></td>
                        <td align ="right"><?php if($split2013[$a]==""){echo '-';}else{echo $split2013[$a];}?></td>
                        <td align ="right"><?php if($split2014[$a]==""){echo '-';}else{echo $split2014[$a];}?></td>
                        <td align ="right"><?php if($split2015[$a]==""){echo '-';}else{echo $split2015[$a];}?></td>
                        <td align ="right"><?php if($split2016[$a]==""){echo '-';}else{echo $split2016[$a];}?></td>
                        <td align ="right"><?php if($split2017[$a]==""){echo '-';}else{echo $split2017[$a];}?></td>
                        <td align ="right"><?php if($split2018[$a]==""){echo '-';}else{echo $split2018[$a];}?></td>
                        <td align ="right"><?php if($split2019[$a]==""){echo '-';}else{echo $split2019[$a];}?></td>
                        <td align ="right"><?php if($split2020[$a]==""){echo '-';}else{echo $split2020[$a];}?></td>
                        <td align ="right"><?php if($split2021[$a]==""){echo '-';}else{echo $split2021[$a];}?></td>
                        <td align ="right"><?php echo $splitTotal[$a];?></td>
                    </tr> 
              <?php } ?>       
            </table>
              <?php }//}?>
            <htmlpagefooter name="page-footer">
                <p style="text-align: right; font-style: italic; color:gray; font-size: 11px;">Prepared By :<?php echo $pdfData['prepared']->name;?> ,Created By : PUBAD - IT Division ,Created Date :<?php echo date("d-m-Y");?></p>
            </htmlpagefooter>
            <?php }else{echo "No data to display.";}?>
        </div>
    </body>
</html>