<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">

                    <div class="panel-heading">
                        <h4>{{ article.title }}</h4>
                    </div>

                    <div class="panel-body">
                        <div  class="bs-callout bs-callout-danger">
                            <p>{{ article.content }}</p>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                article: []
            }
        },
        mounted() {
            const url = '/api/v1/article/info'
            const id = this.$route.params.id
            axios.get(url, {
                    params: {
                        'id': id
                    }
                })
                .then(response => {
                    this.article =  response.data.data
                })
        },
    }
</script>