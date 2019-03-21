<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel</title>

</head>
<body>
    <div id="app">
        <a href='javascript:;' data-clipboard-text="123123" id='code' onclick='copy()'>点击复制</a>
    </div>
</body>


<script src="/js/copy/clipboard.min.js"></script>
<script src="/js/copy/jquery.toast.min.js"></script>


<script>
    function copy(){
        var clipboard = new Clipboard('#code');
        clipboard.on('success', function(code) {
            // console.log(code.text);
            // if($('#'+code.text+':visible').length == 0){
                alert("已复制好优惠码"+code.text+"，可贴粘。");
            // }
        });

        clipboard.on('error', function(e) {
            alert("您的浏览器可能是古董无法完成复制，请手动复制。");
        });
    }
</script>

</html>