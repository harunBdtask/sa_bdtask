<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div style="align-items: center; margin-left: 50px; margin-right: 50px;">
        <div style="display: flex; justify-content: space-between;">
            <p style="align-items: center; display: flex;"><b>Employee information details form of S.A.Agro Feeds Limited under S.A Group</b></p>

            <?php 

                $image_path ='';
                $image_path =$employee_profile_info->image?$employee_profile_info->image:'/assets/dist/img/avatar/avatar-1.png';

            ?>
            <img src="<?php echo base_url('/').$image_path;?>" alt="" style="width: 180px;height: 180px;">
        </div>
        <div>
            <p> Employee ID: Will be fill up by office authority</p>
        </div>
        <div style="display: flex;">
            <p style="margin-bottom: 3px;">Name(Bangla) :-</p>
            <p style="margin-bottom: 3px; margin-left: 10px;"><?php echo $employee_profile_info->bangla_name;?></p>
        </div>
        <div style="display: flex;">
            <p style="margin-bottom: 3px;">Name(English) :-</p>
            <p style="margin-bottom: 3px; margin-left: 10px;"><?php echo $employee_profile_info->first_name.' '.$employee_profile_info->last_name;?></p>
        </div>
        <div style="display: flex;">
            <p style="margin-bottom: 3px;">Designation :-</p>
            <p style="margin-left: 10px; margin-bottom: 3px;"><?php echo $employee_profile_info->emp_designation;?></p>
        </div>
        <div style="display: flex;">
            <p style="margin-bottom: 3px;">Educational Qualification :-</p>
            <p style="margin-left: 10px; margin-bottom: 3px;"><?php echo $employee_profile_info->educational_qualification;?></p>
        </div>
        <div style="display: flex;">
            <p style="margin-bottom: 3px;">Joining Date :-</p>
            <p style="margin-left: 10px; margin-bottom: 3px;"><?php echo $employee_profile_info->joining_date;?></p>
        </div>
        <div style="display: flex;">
            <p style="margin-bottom: 3px;">Gross Salary :-</p>
            <p style="margin-left: 10px; margin-bottom: 3px;"><?php echo $employee_profile_info->gross_salary;?></p>
        </div>
        <div style="display: flex;">
            <p style="margin-bottom: 3px;">Working Place :-</p>
            <p style="margin-left: 10px; margin-bottom: 3px;"><?php echo $employee_profile_info->work_place;?></p>
        </div>
        <div style="display: flex;">
            <p style="margin-bottom: 3px;">Superior :-</p>
            <p style="margin-left: 10px; margin-bottom: 3px;"><?php echo $employee_profile_info->superior_firstname.' '.$employee_profile_info->last_name;?></p>
        </div>
        <div style="display: flex;">
            <p style="margin-bottom: 3px;">Birth Date :-</p>
            <p style="margin-left: 10px; margin-bottom: 3px;"><?php echo $employee_profile_info->birth_date;?></p>
        </div>
        <div style="display: flex;">
            <p style="margin-bottom: 3px;">Nationality :-</p>
            <p style="margin-left: 10px; margin-bottom: 3px;"><?php echo $employee_profile_info->emp_nationality;?></p>
        </div>
        <div style="display: flex;">
            <p style="margin-bottom: 3px;">NID No :-</p>
            <p style="margin-left: 10px; margin-bottom: 3px;"><?php echo $employee_profile_info->nid_no;?></p>
        </div>
        <div style="display: flex;">
            <p style="margin-bottom: 3px;">Father Name :-</p>
            <p style="margin-left: 10px; margin-bottom: 3px;"><?php echo $employee_profile_info->father_name;?></p>
        </div>
        <div style="display: flex;">
            <p style="margin-bottom: 3px;">Mother Name :-</p>
            <p style="margin-left: 10px; margin-bottom: 3px;"><?php echo $employee_profile_info->mother_name;?></p>
        </div>
        <div style="display: flex;">
            <p style="margin-bottom: 3px;">Permanent Address :-</p>
            <p style="margin-left: 10px; margin-bottom: 3px;"><?php echo $employee_profile_info->permanent_address;?></p>
        </div>
        <div style="display: flex;">
            <p style="margin-bottom: 3px; width: 120px;">Present Address :-</p>
            <p style="margin-left: 10px; margin-bottom: 3px;"><?php echo $employee_profile_info->present_address;?></p>
        </div>
        <div style="display: flex;">
            <p style="margin-bottom: 3px;">Mobile No :-</p>
            <p style="margin-bottom: 3px; margin-left: 10px;"><?php echo $employee_profile_info->mobile_no1;?></p>
        </div>
        <div style="display: flex;">
            <p style="margin-bottom: 3px;">Signature & Date :-</p>
            <p style="margin-bottom: 3px; margin-left: 10px;"></p>
        </div>
        <div>
            <p style="margin-bottom: 3px;"> Attachments :- Academic certificates copy & NID copy </p>
        </div>

    </div>

</body>

</html>