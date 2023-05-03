<!DOCTYPE HTML>
<html>
    <head>
        <style type="text/css">
            @page  {  
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
                <h1>Summary Report - <?php if(isset($pdfData['type']) && $pdfData['type']=='min'){echo 'Ministry';}else if(isset($pdfData['type']) && $pdfData['type']='stat_min'){echo 'State Ministry';}else{echo 'Provincial Council';}?></h1> 
            </div>
            <table style="width:100%;" cellspacing="1" border="1" class="page-break">
            <br><br>
              <tr>
                <th valign="top" colspan="2"><b>SN</b></th>
                <th valign="top"><b>Name</b></th>
                <th valign="top"><b>Senior</b></th>
                <th valign="top"><b>Teritary</b></th>
                <th valign="top"><b>Secondary</b></th>
                <th valign="top"><b>Primary</b></th>
                <th valign="top"><b>Total</b></th>
              </tr>
            <?php 
                $d = $pdfData['rpt_data'];
                if(sizeof($d)>0){
                for ($i=0; $i <sizeof($d) ; $i++) {       
                $getAll = DB::select('SELECT SUM(s.total) as all_rec
                            FROM departments d
                            LEFT JOIN senior_posts s ON s.institute_name=d.id
                            WHERE d.deleted_at is null AND d.id='.$d[$i]->id.' AND s.deleted_at IS NULL 
                            UNION
                            SELECT SUM(se.total) 
                            FROM departments d
                            LEFT JOIN secondary_posts se ON se.institute_name=d.id
                            WHERE d.deleted_at IS NULL AND d.id='.$d[$i]->id.' AND se.deleted_at IS NULL 
                            UNION
                            SELECT SUM(t.total) 
                            FROM departments d
                            LEFT JOIN tertiary_posts t ON t.institute_name=d.id
                            WHERE d.deleted_at IS NULL AND d.id='.$d[$i]->id.' AND t.deleted_at IS NULL 
                            UNION 
                            SELECT SUM(p.total)
                            FROM departments d
                            LEFT JOIN primary_posts p ON p.institute_name=d.id
                            WHERE d.deleted_at IS NULL AND d.id='.$d[$i]->id.' AND p.deleted_at IS NULL');         
            ?>
              <tr>
                <td align ="left"><b><?php echo $i+1;?></b></td>
                <td align ="right"><?php //echo $i+1;?></td>
                <td align ="left"><b><?php echo $d[$i]->name;?></b></td>
                <td align ="right"><?php if($getAll[0]->all_rec=='null' || $getAll[0]->all_rec==''){echo '-';}else{echo $getAll[0]->all_rec;}?></td>
                <td align ="right"><?php if($getAll[1]->all_rec=='null' || $getAll[1]->all_rec==''){echo '-';}else{echo $getAll[1]->all_rec;}?></td>
                <td align ="right"><?php if($getAll[2]->all_rec=='null' || $getAll[2]->all_rec==''){echo '-';}else{echo $getAll[2]->all_rec;}?></td>
                <td align ="right"><?php if($getAll[3]->all_rec=='null' || $getAll[3]->all_rec==''){echo '-';}else{echo $getAll[3]->all_rec;}?></td>
                <td align ="right"><?php echo ($getAll[0]->all_rec+$getAll[1]->all_rec+$getAll[2]->all_rec+$getAll[3]->all_rec);?></td>
              </tr>   
            <?php 
                if($d[$i]->sub!=null){
                $splitSub = explode(',',$d[$i]->sub); 
                $splitId  = explode(',',$d[$i]->subId); 
                for ($a=0; $a <sizeof($splitId) ; $a++) {
                    //dd($splitId[$a]);
                    $getSub = DB::select('SELECT SUM(s.total) as all_rec
                            FROM departments d
                            LEFT JOIN senior_posts s ON s.institute_name=d.id
                            WHERE d.deleted_at is null AND d.id='.$splitId[$a].' AND s.deleted_at IS NULL 
                            UNION
                            SELECT SUM(se.total) 
                            FROM departments d
                            LEFT JOIN secondary_posts se ON se.institute_name=d.id
                            WHERE d.deleted_at IS NULL AND d.id='.$splitId[$a].' AND se.deleted_at IS NULL 
                            UNION
                            SELECT SUM(t.total) 
                            FROM departments d
                            LEFT JOIN tertiary_posts t ON t.institute_name=d.id
                            WHERE d.deleted_at IS NULL AND d.id='.$splitId[$a].' AND t.deleted_at IS NULL 
                            UNION 
                            SELECT SUM(p.total)
                            FROM departments d
                            LEFT JOIN primary_posts p ON p.institute_name=d.id
                            WHERE d.deleted_at IS NULL AND d.id='.$splitId[$a].' AND p.deleted_at IS NULL');
                ?>
                <tr>
                    <td align ="left"></td>
                    <td align ="left"><?php echo $a+1;;?></td>
                    <td align ="left"><?php echo $splitSub[$a];?></td>
                    <td align ="right"><?php if($getSub[0]->all_rec=='null' || $getSub[0]->all_rec==''){echo '-';}else{echo $getSub[0]->all_rec;}?></td>
                    <td align ="right"><?php if($getSub[1]->all_rec=='null' || $getSub[1]->all_rec==''){echo '-';}else{echo $getSub[1]->all_rec;}?></td>
                    <td align ="right"><?php if($getSub[2]->all_rec=='null' || $getSub[2]->all_rec==''){echo '-';}else{echo $getSub[2]->all_rec;}?></td>
                    <td align ="right"><?php if($getSub[3]->all_rec=='null' || $getSub[3]->all_rec==''){echo '-';}else{echo $getSub[3]->all_rec;}?></td>
                    <td align ="right"><?php echo ($getSub[0]->all_rec+$getSub[1]->all_rec+$getSub[2]->all_rec+$getSub[3]->all_rec);?></td>
                </tr>    
                <?php } }?>         
            <?php }?>
            </table>
            <htmlpagefooter name="page-footer">
                <p style="text-align: right; font-style: italic; color:gray; font-size: 11px;">Prepared By :<?php echo $pdfData['prepared']->name;?> ,Created By : PUBAD - IT Division ,Created Date :<?php echo date("d-m-Y");?></p>
            </htmlpagefooter>
            <?php }else{echo "No data to display.";}?>
        </div>
    </body>
</html>