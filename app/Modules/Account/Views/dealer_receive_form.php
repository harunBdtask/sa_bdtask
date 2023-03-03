<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="card card-bd lobidrag">
            <div class="card-heading">
                <div class="card-title">
                    <h4>

                    </h4>
                </div>
            </div>
            <div class="card-body">
                <div class="row" id="">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="select" class="col-sm-4 col-form-label"><?php echo get_phrases(['select','dealer']) ?> :
                            </label>
                            <div class="col-sm-8">
                                 <?php echo  form_dropdown('dealer_code',$dealer_list,null, 'class="form-control select2" id="dealer_code"') ?>      
                            </div>
                        </div>
                       
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="select" class="col-sm-4 col-form-label"><?php echo get_phrases(['date']) ?> :
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="date" class="form-control" placeholder="date">      
                            </div>
                    </div>
                    </div>

                       <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="select" class="col-sm-4 col-form-label"><?php echo get_phrases(['payment','type']) ?> :
                            </label>
                            <div class="col-sm-8">
                             <select name="payment_type" class="form-control">
                                 <option value="">Select Payment Type</option>
                                 <option value="1">Cash Payment</option>
                                 <option value="2">Bank Payment</option>
                             </select>      
                            </div>
                    </div>
                    </div>

                      <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="description" class="col-sm-4 col-form-label"><?php echo get_phrases(['description']) ?> :
                            </label>
                            <div class="col-sm-8">
                                <textarea class="form-control" name="description" placeholder="write description"></textarea>      
                            </div>
                    </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>