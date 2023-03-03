
    <style type="text/css">

    .member_inner {
        background: #efefef;
        padding: 25px;
        border: 1px solid #d4d4d4;
        position: relative;
    }

    .member-status {
        color: #fff;
        padding: 4px 10px;
        border-radius: 3px;
        font-size: 12px;
        font-weight: 500;
        max-width: 80px;
    }

    .member-status.present {
        background: #00a72c;
    }

    .member-status.absent {
        background: #d20535;
    }

    .member-status.leave {
        background: #0805d2;
    }

    .member_shift {
        background: #ec9245;
        color: #000000;
        padding: 4px 11px;
        font-size: 12px;
        font-weight: 500;
        border-radius: 4px;
        text-transform: capitalize;
    }

    .member_inner .img_wrapper img {
        width: 75px;
    }

    .member_inner .member_name {
        color: #454545;
        font-size: 17px;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .member_inner .member_position {
        color: #696969;
        font-weight: 400;
    }

    

    .status.present {
        border-color: transparent #009a05 transparent transparent;
    }

    .status.absent {
        border-color: transparent #d20535 transparent transparent;
    }

    .status.leave {
        border-color: transparent #0805d2 transparent transparent;
    }

    .status {
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 0 35px 25px 0;
        position: absolute;
        top: 0;
        right: 0;
    }

    .calendar_width {
        width: 116px;
    }


    .stat-wrap {
        display: -webkit-flex;
        display: -moz-flex;
        display: -ms-flex;
        display: -o-flex;
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }

    .stat-wrap .stat-inner {
        font-size: 20px;
        text-align: center;
        max-width: 250px;
        padding: 15px 30px;
        box-shadow: 0 0 15px rgb(0 0 0 / 10%);
        margin-left: 20px;
        background-color: #fff;
    }

    .stat-wrap .stat-inner:first-child {
        border-right: 0;
    }

    .stat-number {
        display: block;
        font-size: 22px;
    }

    .stat-title {
        font-size: 16px;
    }
    
    </style>


    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h4 class="mb-3"><?php echo get_phrases(['employee','attendance']);?></h4>
                    <input class="form-control calendar_width shiftdate" type="text" name="changedate" onchange="cngdata()"  id="changedrsdate" />
                </div>
                <div class="col-lg-6">
                    <div class="stat-wrap">
                        <div class="stat-title">
                            <h6><?php echo get_phrases(['today','current','shift']);?>:</h6>
                        </div>
                        <div class="stat-inner">
                            <?php 

                            //date_default_timezone_set("Asia/Dhaka");
                            $this->db = db_connect();
                            
                            $cr_time   = date("Y-m-d H:i");
                            // $tomorrow  = date("Y-m-d", strtotime("+1 day"));

                            $builder = $this->db->table('hrm_emproster_assign');
                            $builder->select("hrm_emproster_assign.*");
                            $builder->where('cast(concat(hrm_emproster_assign.emp_startroster_date, " ", hrm_emproster_assign.emp_startroster_time) as datetime) <= ',$cr_time);
                            $builder->where('cast(concat(hrm_emproster_assign.emp_endroster_date," ", hrm_emproster_assign.emp_endroster_time) as datetime) >=',$cr_time);
                            $builder->where('is_complete',1);

                            $attenddata=$builder->countAllResults();


                            $builder2 = $this->db->table('hrm_emproster_assign');
                            $builder2->select("hrm_emproster_assign.*");
                            $builder2->where('cast(concat(emp_startroster_date, " ", emp_startroster_time) as datetime) <= ',$cr_time);
                            $builder2->where('cast(concat(emp_endroster_date," ", emp_endroster_time) as datetime) >=',$cr_time);
                            $builder2->where('is_complete !=',1);

                            $abdata=$builder2->countAllResults();

                            ?>
                            <span class="stat-number"><?php echo $attenddata ;?></span>
                            <span class="stat-title text-success"><?php echo get_phrases(['attend']);?></span>
                        </div>
                        <div class="stat-inner">
                            <span class="stat-number"><?php echo $abdata ;?></span>
                            <span class="stat-title text-danger"><?php echo get_phrases(['absent']);?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>

    <div class="card" id="main_data">
        
    </div>

<script>

    $(document).ready(function() {

        $('.shiftdate').daterangepicker({
            format: 'YYYY-MM-DD',
            singleDatePicker: true,
            showDropdowns: true,
            minDate: 1901,
            maxDate: parseInt(moment().format('YYYY'), 10)
        }, function(start, end, label) {
            var years = moment().diff(start, 'years');
            
        });

        // On ready of the document or the page, this function will be called default and will show current date date/attebdabce shift wise....
        shiftdata();

    });

    function shiftdata(){
		"use strict";

        var submit_url = _baseURL+"human_resources/duty_roster/loadallshift";

        $.ajax({
                type: "POST",
                url: submit_url,
                data:{
                csrf_stream_name:csrf_val,
            },
            success: function(data) {
                $('#main_data').html(data);
            } 
        });
	}

    function cngdata(){
        "use strict";
        
        var cngedate= $('#changedrsdate').val();
        var date    = new Date(cngedate),
        yr          = date.getFullYear(),
        newmonth    = date.getMonth() + 1,
        month       = newmonth < 10 ? '0' + newmonth : newmonth,
        day         = date.getDate()  < 10 ? '0' + date.getDate()  : date.getDate(),
        newDate     = yr + '-' + month + '-' + day;
        var date2   = new Date(),
        yr          = date2.getFullYear(),
        newmonth2   = date2.getMonth() + 1,
        month2      = newmonth2 < 10 ? '0' + newmonth2 : newmonth2,
        day2        = date2.getDate()  < 10 ? '0' + date2.getDate()  : date2.getDate(),
        crntDate    = yr + '-' + month2 + '-' + day2;
       
        var base 	= $('#base_url').val();
        var csrf 	= $('#csrf_token').val();
        var cndate  = newDate;

        var submit_url = _baseURL+"human_resources/duty_roster/loadcngdate";
        
        $.ajax({
                type: "POST",
                url: submit_url,
                data:{
                csrf_stream_name:csrf_val,
                cndate:cndate,
            },
            success: function(data) {
                $('#main_data').hide().html(data).fadeIn();
            } 
        });

    }

</script>
