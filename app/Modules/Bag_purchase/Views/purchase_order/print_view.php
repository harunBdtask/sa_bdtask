<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <nav aria-label="breadcrumb" class="order-sm-last p-0">
                            <ol class="breadcrumb d-inline-flex font-weight-600 fs-17 bg-white mb-0 float-sm-left p-0">
                                <li class="breadcrumb-item"><a href="#"><?php echo $moduleTitle; ?></a></li>
                                <li class="breadcrumb-item active"><?php echo $title; ?></li>
                            </ol>
                        </nav>

                    </div>
                    <div class="text-right">
                        <button type="button" class="btn btn-success" onclick="printContent('printContent')"><?php echo get_phrases(['print']);?></button>
                        <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']); ?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">


                <div class="body-content mb-5">
                    <div id="printContent" class="mt-3 mb-3" style="max-width: 1020px; margin: auto; background-color: #fff; padding: 50px">

                        <div class="row">
                            <div class="col-sm-4">
                                <span><strong>PO No: SAAF/Purchase/2021-0290</strong><span>
                            </div>
                            <div class="col-sm-4 text-center">
                                <h4><u>Purchase Order</u></h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                <span><strong>Date: 13.12.2021</strong><span>
                            </div>
                        </div>

                        <div>
                            <strong>To,</strong> <br />
                            <strong>{supplier_name}</strong> <br />
                            <span>{supplier_address}</span> <br />
                            <span>Mobile: {supplier_mobile}</span>
                        </div>
                        <br />

                        <div>
                            <p><strong>Subject: Purchase Order for Supply {item_name}</strong></p>
                        </div>
                        <br />

                        <div style="text-align: justify;">
                            <p>Against your quotation and verbal negotiated on dated {qdate} and management approval, you are requested to supply of the following item as mentioned bellows on the following terms & condition:</p>
                        </div>
                        <br />

                        <div>
                            <table class="table table-bordered">
                                <tbody style="vertical-align: top; text-align: center">
                                    <tr>
                                        <th>SL No.</th>
                                        <th>Description</th>
                                        <th>Qty</th>
                                        <th>Unit</th>
                                        <th>Unit Price</th>
                                        <th>Total Amount</th>
                                        <th>Remarks</th>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td><strong>{item_name}</strong></td>
                                        <td>24,000</td>
                                        <td>Kg</td>
                                        <td>152.0/-</td>
                                        <td>36,72,000/-</td>
                                        <td>Improted</td>
                                    </tr>
                                    <tr>
                                        <th colspan="5" class="text-right">Total Payable Tk</th>
                                        <th>36,72,000/-</th>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <p><strong>In Words: <?php //echo credit_term(3672000); ?> Taka Only</strong></p>
                        </div>
                        <br />

                        <div>
                            <strong>Terms and Condition:</strong><br />
                            <ol>
                                <li>
                                    {condition_list}
                                </li>
                            </ol>
                        </div>
                        <br />

                        <div>
                            <p>Hope you will agree and supply the item sooner. Your kind cooperation will be highly appreciated.</p>
                        </div>

                        <div>
                            <p>Thanking you,</p>
                        </div>
                        <br />

                        <div>
                            <p>{signature}</p>
                            <p>
                                <strong>{name}</strong><br />
                                <strong>Managing Director</strong>
                            </p>
                            <strong>Copy to:</strong>
                            <strong>
                                <ol>
                                    <li>Chairman</li>
                                    <li>Project Coordinator</li>
                                    <li>GM-Operations</li>
                                    <li>AGM-A/C & Finance</li>
                                    <li>Factory</li>
                                    <li>Office Copy</li>
                                </ol>
                            </strong>
                        </div>

                    </div>
                </div>



            </div>
        </div>
    </div>
</div>