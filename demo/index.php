<html>
    <head>
        <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    </head>
    <body>
        <div id="app">
            <h1>ChatU PHP  Demo</h1>
            <div style="width:800px">
                <div v-for="item in data" :key="item.id">
                    <div v-if="item.type == 'ask'">
                        Q:<div>{{item.text}}</div>
                    </div>
                    <div v-if="item.type == 'answer'">
                        A:<div>{{item.text}}</div>
                    </div>
                </div>
                <div>
                    <div v-if="readingText" v-html="readingText"></div>
                </div>
            </div>
            <div>
                <textarea name="prompt" rows="4" cols="50" v-model="prompt"></textarea>
                    <br/>
                    <br/>
                <button @click="onSubmit">Submit</button>
            </div>
        </div>
        <script>
            const { createApp, ref } = Vue

            createApp({
                setup() {
                    const data = ref([]);
                    const message = ref('Hello vue!');
                    const currentData = ref();
                    const prompt = ref("what is typescript;");
                    const readingText = ref("");
                    function fillAnswer(){
                        console.log("filter_id")
                        data.value.push({type:"answer",text: readingText.value ,streamId : currentData.value});
                        readingText.value ="";
                    }
                    function getEventSource(){
                        var source = new EventSource("http://api.chatuapi.com/chat/stream?streamId="+currentData.value);
                        source.onopen = function () {
                            console.log("onopen");
                        };
                        source.onmessage = function (event) { 
                            if(event.data == "close"){
                                source.close();
                                fillAnswer();
                                return;
                            }
                     
                            if (event.type == "message") {
                                if (event.data === "") {
                                   readingText.value += "\n";
                                } else {
                                   readingText.value += event.data
                                        .replace(/\r/, "\n")
                                        .replace("<c-api-line>", "\n");
                                }
                            } 
                        };
                        source.onerror = function (event) {
                            console.log("onerror", event);
                            source.close(); 
                            fillAnswer();
                        };
                        source.onclose = function (event) {
                            console.log("onclose", event);
                            fillAnswer();
                        };
                    }
                    function onSubmit(){
                        console.log("onSubmit");
                        axios({
                            method: 'post',
                            url: '/demo/createStream.php',
                            data: {
                                prompt: prompt.value
                            }
                        }).then(function (response) {
                          
                            currentData.value = response.data; 
                            data.value.push({type:"ask",text:prompt.value,streamId : currentData.value});
                            getEventSource();
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }
                    return {
                        message,prompt,onSubmit,data,readingText
                    }
                }
            }).mount('#app')
        </script>
    </body>
</html>