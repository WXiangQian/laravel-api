<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">

                    <div class="panel-heading">
                        <h4>{{ article.title }}</h4>
                    </div>

                    <div class="panel-heading">
                        <span>文章类型：{{ article.type }}</span>
                        <span>点赞量：{{ article.vote }}</span>
                        <span style="float:right">更新时间： {{ article.updated_at }}</span>
                    </div>

                    <div class="panel-body">
                        <div  class="bs-callout bs-callout-danger">
                            <p>{{ article.content }}</p>
                        </div>
                    </div>

                    <div class="panel-heading">
                        上一篇：
                        <a href="javascript:void(0);" v-if="article.pre_article == null">暂无文章</a>
                        <router-link :to="{name:'article',params:{id:article.pre_article.id}}" v-else>
                            {{ article.pre_article.title }}
                        </router-link>

                    </div>

                    <div class="panel-heading">
                        下一篇：
                        <a href="javascript:void(0);" v-if="article.next_article == null">暂无文章</a>
                        <router-link :to="{name:'article',params:{id:article.next_article.id}}" v-else>
                            {{ article.next_article.title }}
                        </router-link>

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
        created () {
            // 组件创建完后获取数据，
            // 此时 data 已经被 observed 了
            this.fetchData()
        },
        watch: {
            // 如果路由有变化，会再次执行该方法
            '$route': 'fetchData'
        },
        methods: {
            fetchData() {
                const url = '/api/v1/article/info'
                const id = this.$route.params.id
                axios.get(url, {
                    params: {
                        'id': id
                    }
                }).then(response => {
                        this.article =  response.data.data
                        document.title = this.article.title
                        this.$message({
                            message: '恭喜你，成功获取一篇干货内容',
                            type: 'success',
                            center: true,
                            duration: '5000'
                        });
                    })
                    .catch(err => {
                        this.$message({
                            message: err.response.data.message,
                            type: 'error',
                            center: true,
                        });
                    })
            }
        },
        mounted() {

        },
    }
</script>