<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">文章首页</div>

                    <div class="panel-body">

                        <div v-for="article in articles" :key='article.id' class="bs-callout bs-callout-danger">
                            <h4>
                                <router-link :to="{name:'article',params:{id:article.id}}">
                                    {{ article.title }}
                                </router-link>
                            </h4>
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
                articles: []
            }
        },
        mounted() {
            const url = '/api/v1/article/list'

            axios.get(url, {})
                .then(response => {
                    this.articles =  response.data.data.lists
                })
        },
    }
</script>