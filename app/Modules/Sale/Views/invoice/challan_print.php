<style>
    .col, .col-1, .col-10, .col-11, .col-12, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-auto, .col-lg, .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-auto, .col-md, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-auto, .col-sm, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-auto, .col-xl, .col-xl-1, .col-xl-10, .col-xl-11, .col-xl-12, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6, .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-auto {
    width: 100%;
}
.row {
    flex-wrap: wrap;
 
}
hr {
    margin-top: 1rem;
    margin-bottom: 1rem;
    border: 0;
    border-top: 1px solid rgba(0,0,0,.1);
}

hr {
    box-sizing: content-box;
    height: 0;
    overflow: visible;
}
    .card {
    border: 0;
    box-shadow: 0 0.125rem 0.25rem rgb(0 0 0 / 8%);
}
.card {
    position: relative;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: 0.25rem;
}

.card-body {
    padding: 24px;
    padding: 1.5rem;
}

.card-body {
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    min-height: 1px;
    padding: 1.25rem;
}

.main-content .row [class*=col-] {
    padding-right: 10px;
    padding-left: 10px;
}

.text-center {
    text-align: center!important;
}
@media (min-width: 576px)
.col-sm-12 {
    -ms-flex: 0 0 100%;
    flex: 0 0 100%;
    max-width: 100%;
}

.font-weight-600 {
    font-weight: 600!important;
}

.text-right {
    text-align: right!important;
}
@media (min-width: 576px)
.col-sm-2 {
    -ms-flex: 0 0 16.666667%;
    flex: 0 0 16.666667%;
    max-width: 16.666667%;
}

@media (min-width: 576px)
.col-sm-4 {
    -ms-flex: 0 0 33.333333%;
    flex: 0 0 33.333333%;
    max-width: 33.333333%;
}

.table>thead>tr>th {
    border-bottom: 1px solid #e4e5e7;
}
.table-bordered thead td, .table-bordered thead th {
    border-bottom-width: 2px;
}

.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
}
.table-bordered td, .table-bordered th {
    border: 1px solid #e4e5e7;
}

.table-bordered {
    border: 1px solid #e4e5e7;
}

.table-bordered {
    border: 1px solid #dee2e6;
}
.table {
    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
}
table {
    border-collapse: collapse;
}

.main-content .row [class*=col-] {
    padding-right: 10px;
    padding-left: 10px;
}
.font-weight-600 {
    font-weight: 600!important;
}

.text-right {
    text-align: right!important;
}
label {
    display: inline-block;
}

.table-bordered td, .table-bordered th {
    border: 1px solid #e4e5e7;
}

.table td, .table th {
    padding: 8px 10px;
    border-top-color: #e4e5e7;
}
.table-bordered td, .table-bordered th {
    border: 1px solid #dee2e6;
}
.table td, .table th {
    padding: 0.75rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}
.text-center {
    text-align: center!important;
}


</style>
<link href="<?php echo base_url()?>/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    
<div class="row">
    <div class="col-sm-12">
       <div class="card">
         <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <img src="<?php echo base_url().$settings_info->logo; ?>" alt="Logo" height="40px" ><br><br>
                        <h5><?php echo $settings_info->title; ?></h5>
                        <h6><?php echo 'CHALLAN NO :'. ($do_main?$do_main->challan_no:''); ?></h6>
                        <strong><?php echo get_phrases(['date']).': '.date('d/m/Y'); ?></strong>
                       
                    </div>
                </div>
                <br>
                <div class="row p-4 pt-4">
                <table class="table">
                <tr><td><?php echo get_phrases(['dealer','name']) ?> : <?php echo ($do_main?$do_main->dealer_name:'')?></td><td><?php echo get_phrases(['date']) ?> : <?php echo ($do_main?$do_main->do_date:'')?></td></tr>
                <tr><td><?php echo get_phrases(['dO','no']) ?> : <?php echo ($do_main?$do_main->vouhcer_no:'')?></td><td><?php echo get_phrases(['time']) ?> : <?php echo ($do_main?date("h:i a", strtotime($do_main->created_date)):'')?></td></tr>
              
                </table>
               
                 
                </div>
          

                 

                <table class="table table-bordered">
                    <thead>
                            <tr>
                                <th class="text-center"><?php echo get_phrases(['sl','no']);?></th>
                                <th class="text-center"><?php echo get_phrases(['item','name']);?></th>
                                <th class="text-center"><?php echo get_phrases(['quantity']).'('.get_phrases(['bag']).')';?></th>
                                

                            </tr>
                        </thead>
                        <tbody>
                           <?php if($do_details){
                              $sl = 1;
                              foreach($do_details as $details){?>
                           <tr>
                              <td class="text-center"><?php echo $sl++;?></td>
                              <td class="text-center"><?php echo $details->item_name?></td>
                              <td class="text-right"><?php echo $details->quantity?></td>

                           </tr>
                        <?php }}?>
                        </tbody>
                      
                </table>

                    <div class="form-group row">
               <?php if($do_main->sls_admin_signature){?>
               <label for="name" class="col-sm-2 col-form-label text-center"> <img src="<?php echo base_url($do_main->sls_admin_signature)?>" height="70px;" width="80px;"><div class="border-top"><?php echo get_phrases(['sales','admin'])?></div></label>
               <?php }?>

         <?php if($do_main->accountant_sig){?>
                <label for="name" class="col-sm-2 col-form-label text-center"> <img src="<?php echo base_url($do_main->accountant_sig)?>" height="70px;" width="80px;"><div class="border-top"><?php echo get_phrases(['accountant','signature'])?></div></label>
                <?php }?>

           


           <?php if($do_main->dl_s_sig){?>
                <label for="name" class="col-sm-3 col-form-label text-center"> <img src="<?php echo base_url($do_main->dl_s_sig)?>" height="70px;" width="80px;"><div class="border-top"><?php echo get_phrases(['delivery','section','admin','signature'])?></div></label>
                <?php }?> 

                <?php if($do_main->str_s_sig){?>
                <label for="name" class="col-sm-3 col-form-label text-center"> <img src="<?php echo base_url($do_main->str_s_sig)?>" height="70px;" width="80px;"><div class="border-top"><?php echo get_phrases(['store','admin','signature'])?></div></label>
                <?php }?>   
                
                <?php if($do_main->fc_m_sig){?>
                <label for="name" class="col-sm-2 col-form-label text-center"> <img src="<?php echo base_url($do_main->fc_m_sig)?>" height="70px;" width="80px;"><div class="border-top"><?php echo get_phrases(['factory','manager'])?></div></label>
                <?php }?> 
            </div>

         </div>
       </div>
     </div>
</div> 

  