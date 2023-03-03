       <!-- Human Resource module -->
                    <?php if($permission->check_label('human_resources')->access()){ ?>
                    <li class="<?php echo (($seg_1=="human_resources")?"mm-active":'') ?>">
                          <a class="has-arrow material-ripple" href="#">
                              <i class="typcn typcn-group-outline <?php echo $left;?>"></i>
                              <?php echo get_phrases(['human_resources', 'resource'])?> 
                          </a>
                      <ul class="nav-second-level <?php echo (($seg_1=="human_resources")?"mm-collapse mm-show":'') ?> ">
                      <?php if($permission->module('job_title')->access()){ ?>
                        <li class="<?php echo (($seg_2=="employees" && $seg_3=="jobTitle")?"mm-active":'') ?>"><a href="<?php echo base_url('human_resources/employees/jobTitle')?>"> <?php echo get_phrases(['job', 'title'])?></a></li>
                      <?php }if($permission->module('employee')->access()){ ?>
                        <li class="<?php echo (($seg_2=="employees" && ($seg_3=="home" || $seg_3=="profile"))?"mm-active":'') ?>"><a href="<?php echo base_url('human_resources/employees/home')?>"> <?php echo get_phrases(['employee'])?></a></li>
                      <?php }?>

                      <!-- Start Attendance -->
                      <?php if($permission->module('attendance_form')->access() || $permission->module('attendance_log')->access() || $permission->module('attendance_setup')->access()){ ?>
                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false"><?php echo get_phrases(['attendance'])?></a>
                            <ul class="nav-third-level">

                               <?php if($permission->module('attendance_form')->access()){ ?>
                                  <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="attendance" && $seg_3=="attendance_form")?"mm-active":'') ?>">
                                      <a href="<?php echo base_url('human_resources/attendance/attendance_form')?>" ><?php echo get_phrases(['attendance','form'])?></a>
                                  </li>
                                <?php }?>

                                <?php if($permission->module('attendance_log')->access()){ ?>
                                  <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="attendance" && $seg_3=="attendance_log")?"mm-active":'') ?>">
                                      <a href="<?php echo base_url('human_resources/attendance/attendance_log')?>" ><?php echo get_phrases(['attendance','log'])?></a>
                                  </li>
                                <?php }?>

                                <?php if($permission->module('attendance_setup')->access()){ ?>
                                  <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="attendance" && $seg_3=="attendance_setup")?"mm-active":'') ?>">
                                      <a href="<?php echo base_url('human_resources/attendance/attendance_setup')?>" ><?php echo get_phrases(['attendance','setup'])?></a>
                                  </li>
                                <?php }?>

                            </ul>
                        </li>

                      <?php } ?>

                      <!-- End Attendnace -->
                      
                      <!-- Start Company structure-->
                      <?php if($permission->module('departments')->access() || $permission->module('members')->access() || $permission->module('tree_view')->access()){ ?>
                        <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="company_structure" && ($seg_3=="tree_view" || $seg_3=="departments" || $seg_3=="members"))?"mm-active":'') ?>">

                            <a class="has-arrow" href="#" aria-expanded="false"><?php echo get_phrases(['company','structure'])?></a>
                            <ul class="nav-third-level">

                               <?php if($permission->module('tree_view')->access()){ ?>
                                  <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="company_structure" && $seg_3=="tree_view")?"mm-active":'') ?>">
                                      <a href="<?php echo base_url('human_resources/company_structure/tree_view')?>" ><?php echo get_phrases(['tree','view'])?></a>
                                  </li>
                                <?php }?>

                               <?php if($permission->module('departments')->access()){ ?>
                                  <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="company_structure" && $seg_3=="departments")?"mm-active":'') ?>">
                                      <a href="<?php echo base_url('human_resources/company_structure/departments')?>" ><?php echo get_phrases(['departments'])?></a>
                                  </li>
                                <?php }?>

                                 <?php if($permission->module('members')->access()){ ?>
                                  <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="company_structure" && $seg_3=="members")?"mm-active":'') ?>">
                                      <a href="<?php echo base_url('human_resources/company_structure/members')?>" ><?php echo get_phrases(['members'])?></a>
                                  </li>
                                <?php }?>

                            </ul>
                        </li>

                      <?php } ?>
                      <!-- End company structure -->

                      <!-- Start Company documents-->

                      <?php if($permission->module('company')->access() || $permission->module('personal')->access() || $permission->module('letters')->access()){ ?>
                        <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="company_documents" && ($seg_3=="company" || $seg_3=="personal" || $seg_3=="letters"))?"mm-active":'') ?>">

                            <a class="has-arrow" href="#" aria-expanded="false"><?php echo get_phrases(['company','documents'])?></a>
                            <ul class="nav-third-level">

                               <?php if($permission->module('company')->access()){ ?>
                                  <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="company_documents" && $seg_3=="company")?"mm-active":'') ?>">
                                      <a href="<?php echo base_url('human_resources/company_documents/company')?>" ><?php echo get_phrases(['company'])?></a>
                                  </li>
                                <?php }?>

                               <?php if($permission->module('personal')->access()){ ?>
                                  <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="company_documents" && $seg_3=="personal")?"mm-active":'') ?>">
                                      <a href="<?php echo base_url('human_resources/company_documents/personal')?>" ><?php echo get_phrases(['personal'])?></a>
                                  </li>
                                <?php }?>

                                 <?php if($permission->module('letters')->access()){ ?>
                                  <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="company_documents" && $seg_3=="letters")?"mm-active":'') ?>">
                                      <a href="<?php echo base_url('human_resources/company_documents/letters')?>" ><?php echo get_phrases(['letters'])?></a>
                                  </li>
                                <?php }?>

                            </ul>
                        </li>

                      <?php } ?>

                      <!-- End company documents -->

                      <!-- Start Requests and Approvals-->
                      <?php if($permission->module('loan_request')->access() || $permission->module('loan_type')->access() || $permission->module('loan_approval')->access() || $permission->module('leave_request')->access() || $permission->module('leave_type')->access() || $permission->module('leave_approval')->access() || $permission->module('allowance_request')->access() || $permission->module('allowance_type')->access() || $permission->module('allowance_approval')->access() || $permission->module('attendance_request')->access() || $permission->module('attendance_approval')->access()){ ?>
                        
                          <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="request_approval" && ($seg_3=="loan_request" || $seg_3=="loan_type" || $seg_3=="loan_approval" || $seg_3=="leave_request" || $seg_3=="leave_type" || $seg_3=="leave_approval" || $seg_3=="allowance_request" || $seg_3=="allowance_type" || $seg_3=="allowance_approval" || $seg_3=="attendance_request" || $seg_3=="attendance_approval"))?"mm-active":'') ?>">

                            <a class="has-arrow material-ripple" href="#"><?php echo get_phrases(['request','&','approval'])?></a>

                            <ul class="nav-third-level <?php echo (($seg_1=="human_resources" && $seg_2=="request_approval" && ($seg_3=="loan_request" || $seg_3=="loan_type" || $seg_3=="loan_approval" || $seg_3=="leave_request" || $seg_3=="leave_type" || $seg_3=="leave_approval" || $seg_3=="allowance_request" || $seg_3=="allowance_type" || $seg_3=="allowance_approval" || $seg_3=="attendance_request" || $seg_3=="attendance_approval"))?"mm-collapse mm-show":'') ?>">
                                <?php if($permission->module('loan_request')->access() || $permission->module('loan_type')->access() || $permission->module('loan_approval')->access()){ ?>
                                    <li>
                                        <a class="has-arrow" href="#" aria-expanded="false"><?php echo get_phrases(['loan'])?></a>
                                        <ul class="nav-fourth-level">
                                            <?php if($permission->module('loan_type')->access()){ ?>
                                                <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="request_approval" && $seg_3=="loan_type")?"mm-active":'') ?>"><a href="<?php echo base_url('human_resources/request_approval/loan_type')?>"> <?php echo get_phrases(['loan','type'])?></a></li>
                                            <?php }if($permission->module('loan_request')->access()){ ?>
                                                <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="request_approval" && $seg_3=="loan_request")?"mm-active":'') ?>"><a href="<?php echo base_url('human_resources/request_approval/loan_request')?>"> <?php echo get_phrases(['loan','request'])?></a></li>
                                            <?php }if($permission->module('loan_approval')->access()){ ?>
                                                <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="request_approval" && $seg_3=="loan_approval")?"mm-active":'') ?>"><a href="<?php echo base_url('human_resources/request_approval/loan_approval')?>"> <?php echo get_phrases(['loan','approval'])?></a></li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php }?>
                                <?php if($permission->module('leave_request')->access() || $permission->module('leave_type')->access() || $permission->module('leave_approval')->access()){ ?>
                                    <li>
                                        <a class="has-arrow" href="#" aria-expanded="false"><?php echo get_phrases(['leave'])?></a>
                                        <ul class="nav-fourth-level">
                                            <?php if($permission->module('leave_type')->access()){ ?>
                                                <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="request_approval" && $seg_3=="leave_type")?"mm-active":'') ?>"><a href="<?php echo base_url('human_resources/request_approval/leave_type')?>"> <?php echo get_phrases(['leave','type'])?></a></li>
                                            <?php }if($permission->module('leave_request')->access()){ ?>
                                                <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="request_approval" && $seg_3=="leave_request")?"mm-active":'') ?>"><a href="<?php echo base_url('human_resources/request_approval/leave_request')?>"> <?php echo get_phrases(['leave','request'])?></a></li>
                                            <?php }if($permission->module('leave_approval')->access()){ ?>
                                                <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="request_approval" && $seg_3=="leave_approval")?"mm-active":'') ?>"><a href="<?php echo base_url('human_resources/request_approval/leave_approval')?>"> <?php echo get_phrases(['leave','approval'])?></a></li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php }?>
                                <?php if($permission->module('allowance_request')->access() || $permission->module('allowance_type')->access() || $permission->module('allowance_approval')->access()){ ?>
                                    <li>
                                        <a class="has-arrow" href="#" aria-expanded="false"><?php echo get_phrases(['allowance'])?></a>
                                        <ul class="nav-fourth-level">
                                            <?php if($permission->module('allowance_type')->access()){ ?>
                                                <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="request_approval" && $seg_3=="allowance_type")?"mm-active":'') ?>"><a href="<?php echo base_url('human_resources/request_approval/allowance_type')?>"> <?php echo get_phrases(['allowance','type'])?></a></li>
                                            <?php }if($permission->module('allowance_request')->access()){ ?>
                                                <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="request_approval" && $seg_3=="allowance_request")?"mm-active":'') ?>"><a href="<?php echo base_url('human_resources/request_approval/allowance_request')?>"> <?php echo get_phrases(['allowance','request'])?></a></li>
                                            <?php }if($permission->module('allowance_approval')->access()){ ?>
                                                <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="request_approval" && $seg_3=="allowance_approval")?"mm-active":'') ?>"><a href="<?php echo base_url('human_resources/request_approval/allowance_approval')?>"> <?php echo get_phrases(['allowance','approval'])?></a></li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php }?>
                                <?php if($permission->module('attendance_request')->access() || $permission->module('attendance_approval')->access()){ ?>
                                    <li>
                                        <a class="has-arrow" href="#" aria-expanded="false"><?php echo get_phrases(['attendance'])?></a>
                                        <ul class="nav-fourth-level">
                                            <?php if($permission->module('attendance_request')->access()){ ?>
                                                <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="request_approval" && $seg_3=="attendance_request")?"mm-active":'') ?>"><a href="<?php echo base_url('human_resources/request_approval/attendance_request')?>"> <?php echo get_phrases(['attendance','request'])?></a></li>
                                            <?php }if($permission->module('attendance_approval')->access()){ ?>
                                                <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="request_approval" && $seg_3=="attendance_approval")?"mm-active":'') ?>"><a href="<?php echo base_url('human_resources/request_approval/attendance_approval')?>"> <?php echo get_phrases(['attendance','approval'])?></a></li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php }?>
                            </ul>
                          </li>
                      <?php } ?>
                      <!-- End Requests and Approvals -->

                      <!-- Start Duty Roster-->

                      <?php if($permission->module('shift')->access() || $permission->module('roster')->access() || $permission->module('roster_assign')->access() || $permission->module('attendance_dashboard')->access()){ ?>
                        
                        <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="duty_roster" && ($seg_3=="shift" || $seg_3=="roster" || $seg_3=="roster_assign" || $seg_3=="attendance_dashboard" || $seg_3=="roster_shift_assign" || $seg_3 == "shiftRosterList" || $seg_3 == "update_shiftAssignForm"))?"mm-active":'') ?>">

                            <a class="has-arrow" href="#" aria-expanded="false"><?php echo get_phrases(['duty','roster'])?></a>
                            <ul class="nav-third-level">

                               <?php if($permission->module('shift')->access()){ ?>
                                  <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="duty_roster" && $seg_3=="shift")?"mm-active":'') ?>">
                                      <a href="<?php echo base_url('human_resources/duty_roster/shift')?>" ><?php echo get_phrases(['shift'])?></a>
                                  </li>
                                <?php }?>

                               <?php if($permission->module('roster')->access()){ ?>
                                  <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="duty_roster" && $seg_3=="roster")?"mm-active":'') ?>">
                                      <a href="<?php echo base_url('human_resources/duty_roster/roster')?>" ><?php echo get_phrases(['roster'])?></a>
                                  </li>
                                <?php }?>

                                <?php if($permission->module('roster_assign')->access()){ ?>
                                  <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="duty_roster" && $seg_3=="roster_assign" || $seg_3=="roster_shift_assign" || $seg_3 == "shiftRosterList" || $seg_3 == "update_shiftAssignForm")?"mm-active":'') ?>">
                                      <a href="<?php echo base_url('human_resources/duty_roster/roster_assign')?>" ><?php echo get_phrases(['roster','assign'])?></a>
                                  </li>
                                <?php }?>

                                <?php if($permission->module('attendance_dashboard')->access()){ ?>
                                  <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="duty_roster" && $seg_3=="attendance_dashboard")?"mm-active":'') ?>">
                                      <a href="<?php echo base_url('human_resources/duty_roster/attendance_dashboard')?>" ><?php echo get_phrases(['attendance','dashboard'])?></a>
                                  </li>
                                <?php }?>

                            </ul>
                        </li>

                      <?php } ?>

                      <!-- End Duty Roster -->

                      <!-- Start Employee salary-->

                      <?php if($permission->module('penalty')->access() || $permission->module('salary_setup')->access() || $permission->module('salary_generate')->access() || $permission->module('salary_report')->access()){ ?>

                        <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="employee_salary" && ($seg_3=="penalty" || $seg_3=="salary_setup" || $seg_3=="salary_generate" || $seg_3=="salary_report"))?"mm-active":'') ?>">

                            <a class="has-arrow" href="#" aria-expanded="false"><?php echo get_phrases(['employee','salary'])?></a>
                              <ul class="nav-third-level">

                                <?php if($permission->module('penalty')->access()){ ?>
                                  <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="employee_salary" && $seg_3=="penalty")?"mm-active":'') ?>">
                                      <a href="<?php echo base_url('human_resources/employee_salary/penalty')?>" ><?php echo get_phrases(['penalty'])?></a>
                                  </li>
                                <?php }?>

                                <?php if($permission->module('salary_setup')->access()){ ?>
                                  <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="employee_salary" && $seg_3=="salary_setup")?"mm-active":'') ?>">
                                      <a href="<?php echo base_url('human_resources/employee_salary/salary_setup')?>" ><?php echo get_phrases(['salary','setup'])?></a>
                                  </li>
                                <?php }?>

                                <?php if($permission->module('salary_generate')->access()){ ?>
                                  <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="employee_salary" && $seg_3=="salary_generate")?"mm-active":'') ?>">
                                      <a href="<?php echo base_url('human_resources/employee_salary/salary_generate')?>" ><?php echo get_phrases(['salary','generate'])?></a>
                                  </li>
                                <?php }?>

                                <?php if($permission->module('week_ends')->access()){ ?>
                                  <li class="<?php echo (($seg_1=="human_resources" && $seg_2=="employee_salary" && $seg_3=="week_ends")?"mm-active":'') ?>">
                                      <a href="<?php echo base_url('human_resources/employee_salary/week_ends')?>" ><?php echo get_phrases(['weekends'])?></a>
                                  </li>
                                <?php }?>

                              </ul>

                        </li>

                      <?php } ?>

                      <!-- End Employee salary -->

                      </ul>
                    </li>
                    <?php } ?>
                    <!-- END Human Resource module -->