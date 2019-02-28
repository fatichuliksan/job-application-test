<?php

function messageAlert($message = null, $type = 'success')
{
    return '<div id="alert-custom">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">
                            ' . $message . '
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                     </div>
                </div>
            </div> 
            <script>setTimeout(function(){ $("#alert-custom").html("") }, 3000)</script>';
}
