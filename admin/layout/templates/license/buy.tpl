<div class="container-fluid" style="min-height: 1000px">
    <style type="text/css">
        iframe {
            border: 0 none;
            width: 100%;
            height: 100%;
            position: absolute;
            left: 78px;
            z-index: 9;
        }
        .left-side {
            position: absolute;
            z-index: 10; 
        }
    </style>

    <iframe scrolling="no" src="{$baseUrl}/plans?authToken={$authToken}&callbackUrlToken={$callbackUrlToken}" seamless></iframe>
</div>