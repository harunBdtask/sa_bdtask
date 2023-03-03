<script type="text/javascript">
    var callBackFunction;
    function ExtraLargeModal(url, header)
    {
        // loading the modal
        jQuery('#extra-large-modal').modal('show', {backdrop: 'static'});
        // Show success response
        $.ajax({
          url: url,
          success: function(response)
          {
            jQuery('#extra-large-modal .modal-body').html(response);
            jQuery('#extra-large-modal .modal-title').html(header);
          }
        });
    }


    function largeModal(url, header, )
    {
      // loading the modal
      jQuery('#large-modal').modal('show', {backdrop: 'true'});

      // Show success response
      $.ajax({
        url: url,
        success: function(response)
        {
          jQuery('#large-modal .modal-body').html(response);
          jQuery('#large-modal .modal-title').text(header);
        }
      });
    }

    function midiumModal(url, header, )
    {
      // loading the modal
      jQuery('#midium-modal').modal('show', {backdrop: 'true'});
      console.log(url);
      // Show success response
      $.ajax({
        url: url,
        success: function(response)
        {
          jQuery('#midium-modal .modal-body').html(response);
          jQuery('#midium-modal .modal-title').text(header);
        }
      });
    }


    function confirmModal(delete_url, param)
    {
        //Loading the Modal
        jQuery('#alert-modal').modal('show', {backdrop: 'static'});
        callBackFunction = param;
        document.getElementById('delete_form').setAttribute('action' , delete_url);
    }
</script>
<!-- Extra large modal button -->
<div class="modal fade bd-example-modal-xl" id="extra-large-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel2"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
            </div>
        </div>
    </div>
</div>
<!-- Large modal button -->
<div class="modal fade bd-example-modal-lg" id="large-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel3"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
            </div>
        </div>
    </div>
</div>

<!-- medium modal button -->
<div class="modal fade bd-example-modal-md" id="midium-modal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="mediumModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
            </div>
        </div>
    </div>
</div>
<!-- Small modal -->
<div class="modal fade bd-example-modal-sm" id="alert-modal" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="alertModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
            </div>
        </div>
    </div>
</div>
<!-- Modal: Notifications Activity -->
<div class="modal fade" id="sidebarModalActivity" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-vertical" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title font-weight-bold">Notifications</h6>
                <a href="#">Mark all as read</a>
            </div>
            <div class="modal-body">
                <!-- List group -->
                <div class="list-group list-group-flush my-n3">
                    <a class="list-group-item text-reset px-0" href="#">
                        <div class="media">
                            <!--avatar-->
                            <div class="avatar avatar-sm mr-3">
                                <img src="assets/dist/img/avatar-1.jpg" alt="..." class="avatar-img rounded-circle">
                            </div>
                            <div class="media-body">
                                <div class="text-muted fs-13">
                                    <strong class="text-inverse">Melissa Ayre </strong> Re: New security codes. Hello again and thanks for being part...
                                </div>
                                <small class="text-muted">2m ago</small>
                            </div>
                        </div>
                    </a>
                    <a class="list-group-item text-reset px-0" href="#">
                        <div class="media">
                            <!--avatar-->
                            <div class="avatar avatar-sm mr-3">
                                <img src="assets/dist/img/avatar-2.jpg" alt="..." class="avatar-img rounded-circle">
                            </div>
                            <div class="media-body">
                                <div class="text-muted fs-13">
                                    <strong class="text-inverse">Ab Hadley</strong> Hi, How are you? What about our next meeting 😍
                                </div>
                                <small class="text-muted">2m ago</small>
                            </div>
                        </div>
                    </a>
                    <a class="list-group-item text-reset px-0" href="#">
                        <div class="media">
                            <!--avatar-->
                            <div class="avatar avatar-sm mr-3">
                                <img src="assets/dist/img/avatar-3.jpg" alt="..." class="avatar-img rounded-circle">
                            </div>
                            <div class="media-body">
                                <div class="text-muted fs-13">
                                    <strong class="text-inverse">Mario Drummond</strong> commented <blockquote class="mb-0">“I don’t think this really makes sense to do without approval from Johnathan since he’s the one...” </blockquote>❤️ ❤️ ❤️
                                </div>
                                <small class="text-muted">2m ago</small>
                            </div>
                        </div>
                    </a>
                    <a class="list-group-item text-reset px-0" href="#">
                        <div class="media">
                            <!--avatar-->
                            <div class="avatar avatar-sm mr-3">
                                <img src="assets/dist/img/avatar-4.jpg" alt="..." class="avatar-img rounded-circle">
                            </div>
                            <div class="media-body">
                                <div class="text-muted fs-13">
                                    <strong class="text-inverse">Karen Robinson</strong> Wow ! 🤑 this admin looks good and awesome design
                                </div>
                                <small class="text-muted">2m ago</small>
                            </div>
                        </div>
                    </a>
                    <a class="list-group-item text-reset px-0" href="#">
                        <div class="media">
                            <!--avatar-->
                            <div class="avatar avatar-sm mr-3">
                                <img src="assets/dist/img/avatar-5.jpg" alt="..." class="avatar-img rounded-circle">
                            </div>
                            <div class="media-body">
                                <div class="text-muted fs-13">
                                    <strong class="text-inverse">Miyah Myles</strong> shared your post with Ryu Duke,🏆🥇 Glen Rouse, and 3 others.
                                </div>
                                <small class="text-muted">2m ago</small>
                            </div>
                        </div>
                    </a>
                    <a class="list-group-item text-reset px-0" href="#">
                        <div class="media">
                            <!--avatar-->
                            <div class="avatar avatar-sm mr-3">
                                <img src="assets/dist/img/avatar-6.jpg" alt="..." class="avatar-img rounded-circle">
                            </div>
                            <div class="media-body">
                                <div class="text-muted fs-13">
                                    <strong class="text-inverse">Ryu Duke</strong> reacted to your post with a 😍
                                </div>
                                <small class="text-muted">2m ago</small>
                            </div>
                        </div>
                    </a>
                    <a class="list-group-item text-reset px-0" href="#">
                        <div class="media">
                            <!--avatar-->
                            <div class="avatar avatar-sm mr-3">
                                <img src="assets/dist/img/avatar-2.jpg" alt="..." class="avatar-img rounded-circle">
                            </div>
                            <div class="media-body">
                                <div class="text-muted fs-13">
                                    <strong class="text-inverse">Oliver Kopyuv</strong> commented <blockquote class="mb-0">“I don’t think this really makes sense to do without approval from Johnathan since he’s the one...” </blockquote>
                                </div>
                                <small class="text-muted">2m ago</small>
                            </div>
                        </div>
                    </a>
                    <a class="list-group-item text-reset px-0" href="#">
                        <div class="media">
                            <!--avatar-->
                            <div class="avatar avatar-sm mr-3">
                                <img src="assets/dist/img/avatar-2.jpg" alt="..." class="avatar-img rounded-circle">
                            </div>
                            <div class="media-body">
                                <div class="text-muted fs-13">
                                    <strong class="text-inverse">Tracey Chang</strong> subscribed to you.🏀💪
                                </div>
                                <small class="text-muted">2m ago</small>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>