<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Chat Client</title>
</head>
<body>
<div style="width:600px;margin:0 auto;border:1px solid #ccc;">
    <div id="content" style="overflow-y:auto;height:300px;"></div>
    <hr />
    <div style="height:40px;background:white;">
        <input type="text" class="form-control" id="message"  placeholder="请输入内容">
        <button type="button" class="btn btn-primary" onclick="sendMessage()">Primary</button>
    </div>
</div>

<script type="text/javascript">
    if(window.WebSocket){
        // 端口和ip地址对应不要写错
        var webSocket = new WebSocket("ws://127.0.0.1:5200");
        webSocket.onopen = function (event) {
            console.log('webSocket 链接成功');
        };
        // 连接关闭时触发
        webSocket.onclose = function (event) {
            console.log("WebSocket 关闭连接");
        }

        //收到服务端消息回调
        webSocket.onmessage = function (event) {
            var content = document.getElementById('content');
            content.innerHTML = content.innerHTML.concat('<p style="margin-left:20px;height:20px;line-height:20px;">'+event.data+'</p>');
            console.log(event.data)
        }

        var sendMessage = function(){
            var data = document.getElementById('message').value;
            webSocket.send(data);
        }
    }else{
        console.log("您的浏览器不支持WebSocket");
    }
</script>


</body>
</html>