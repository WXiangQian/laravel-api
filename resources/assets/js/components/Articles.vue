<el-row>
   <el-col :span="24"><div class="grid-content bg-purple-dark">1111</div></el-col>
</el-row>
<template>
   <el-table
           :data="articles"
           style="width: 100%"
           :default-sort = "{prop: 'vote', order: 'updated_at'}"
   >
      <el-table-column
              prop="id"
              label="id"
              width="180">
      </el-table-column>
      <el-table-column
              prop="title"
              label="标题"
              width="360">
      </el-table-column>
      <el-table-column
              prop="type"
              label="类型">
      </el-table-column>
      <el-table-column
              prop="updated_at"
              sortable
              label="更新时间">
      </el-table-column>
      <el-table-column
              prop="vote"
              sortable
              label="点赞量">
      </el-table-column>
   </el-table>
</template>
<style>
   .el-table .warning-row {
      background: oldlace;
   }

   .el-table .success-row {
      background: #f0f9eb;
   }
</style>

<script>
    export default {
        mounted() {
            const url = '/api/v1/article/list'

            axios.get(url, {})
                .then(response => {
                    this.articles =  response.data.data
                })

        },
        methods: {
            formatter(row, column) {
                return row.address;
            }
        },
        data() {
            return {
                articles: []
            }
        }
    }
</script>