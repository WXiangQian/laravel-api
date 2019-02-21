<template>
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
                <div class="block">
                    <div class="pagination-container">
                        <el-pagination
                                @size-change="handleSizeChange"
                                @current-change="handleCurrentChange"
                                :current-page.sync="currentPage"
                                layout="total, prev, pager, next, jumper"
                                :total="data.total">
                        </el-pagination>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
    export default {
        data(){
            return {
                currentPage:1, //初始页
                articles: [],
                data: [],
            }
        },
        created() {
            this.handleCurrentChange(this.currentPage)
        },
        methods: {
            handleCurrentChange: function(currentPage = 1){
                //console.log(this.currentPage)  //点击第几页
                const url = '/api/v1/article/list'

                axios.get(url, {
                    params: {
                        'page' :currentPage
                    }
                })
                    .then(response => {
                        this.articles =  response.data.data.lists
                        this.data =  response.data.data
                    })
            },
        }
    }
</script>