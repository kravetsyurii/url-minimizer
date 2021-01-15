@extends('layout')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm"></div>
        <div class="col-sm">
            <form id="form">
                <div class="mb-3">
                    <label class="form-label">Enter your URL:</label>
                    <input type="text" name="url" class="form-control">
                    <div class="error text-danger"></div>
                </div>
                <label for="basic-url" class="form-label">Your custom URL (optional):</label>
                <div class="input-group mb-3">
                    <span class="input-group-text">{{$_SERVER['SERVER_NAME'].'/'}}</span>
                    <input type="text" name="customUrl" class="form-control" id="basic-url">
                    <div class="error text-danger"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Link will live till:</label>
                    <input type="datetime-local" name="lifeEndDate" class="form-control">
                    <div class="error text-danger"></div>
                </div>
                <div class="text-center">
                    <button type="button" id="submit" class="btn btn-primary">Minimize it</button>
                </div>
            </form>
        </div>
        <div class="col-sm"></div>
    </div>
    <div id="response-block" class="row" style="margin-top: 15px; visibility: hidden;">
        <div class="col-sm"></div>
        <div class="col-sm">
            <div class="mb-3">
                <label class="form-label">Your url:</label>
                <input id="response" type="text" class="form-control">
            </div>
            <div class="mb-3 text-center">
                <button class="btn btn-success" onclick="copyToClipboard('#response')">Copy</button>
            </div>
            <div class="mb-3 text-center">
                <p >You can show statistic by follow this link:</p>
                <a id="stats-link" target="_blank"></a>
            </div>
        </div>
        <div class="col-sm"></div>
    </div>
</div>
<script>
    $("#submit").on("click", function () {
        $(".error").text('');
        let data = $("#form").serialize();
        $.ajax({
            type: 'POST',
            url: 'api/url/create',
            data: data,
            success: function(response) {
                //console.log(response);
                $("#response").attr("value", response['shortLink']);
                $("#response-block").css("visibility", "visible");
                $("#stats-link").attr('href', response['statsLink']).text(response['statsLink']);
            },
            error:  function(err){
                //console.log(err.responseJSON.errors);
                let errorMessages;
                $.each(err.responseJSON.errors ,function(index,value){
                    errorMessages = '';
                    $.each(value, function (i, errMessage) {
                        errorMessages+="<p>"+errMessage+"</p>"
                    });
                    $('input[name = '+index+']').next(".error").append(errorMessages);
                });
            }
        });
    });
</script>
<script>
    function copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        var text = $(element).val()
        $temp.val(text).select();
        document.execCommand("copy");
        $temp.remove();
        alert("Link copied: "+text);
    }

</script>
@endsection
