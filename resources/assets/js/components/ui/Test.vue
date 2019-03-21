<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="panel panel-default">
                    <div class="panel-heading"><h1 >这是test.vue部分</h1></div>

                    <div class="panel-body">
                        <el-button :plain="true" @click="open2">成功</el-button>
                        <el-button :plain="true" @click="open3">警告</el-button>
                        <el-button :plain="true" @click="open">消息</el-button>
                        <el-button :plain="true" @click="open4">错误</el-button>
                        <br><br>

                        <el-button
                                type="primary"
                                @click="openFullScreen">
                            点击Loading
                        </el-button>
                        <br><br>

                        <el-button @click="show = !show" type="success" round>fade 淡入淡出</el-button>
                        <div style="display: flex; margin: 20px 350px; height: 100px;">
                            <transition name="el-fade-in-linear">
                                <div v-show="show" class="transition-box">我是vue1</div>
                            </transition>
                            <transition name="el-fade-in">
                                <div v-show="show" class="transition-box">我是vue2</div>
                            </transition>
                        </div>

                        <div>

                            <el-rate
                                    v-model="value1"
                                    show-text>
                            </el-rate>

                            <el-transfer
                                    style="text-align: left; display: inline-block"
                                    v-model="value4"
                                    filterable
                                    :left-default-checked="[2, 3]"
                                    :right-default-checked="[1]"
                                    :titles="['Source', 'Target']"
                                    :button-texts="['到左边', '到右边']"
                                    :format="{
                                        noChecked: '${total}',
                                        hasChecked: '${checked}/${total}'
                                    }"
                                    @change="handleChange"
                                    :data="data">
                                <span slot-scope="{ option }">{{ option.key }} - {{ option.label }}</span>
                                <el-button class="transfer-footer" slot="left-footer" size="small">操作</el-button>
                                <el-button class="transfer-footer" slot="right-footer" size="small">操作</el-button>
                            </el-transfer>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('test.vue');
        },
        methods: {
            open() {
                this.$message({
                    showClose: true,
                    message: '这是一条消息提示'
                });
            },

            open2() {
                this.$message({
                    showClose: true,
                    message: '恭喜你，这是一条成功消息',
                    type: 'success'
                });
            },

            open3() {
                this.$message({
                    showClose: true,
                    message: '警告哦，这是一条警告消息',
                    type: 'warning'
                });
            },

            open4() {
                this.$message({
                    showClose: true,
                    message: '错了哦，这是一条错误消息',
                    type: 'error'
                });
            },
            handleChange(value, direction, movedKeys) {
                console.log(value, direction, movedKeys);
            },
            openFullScreen() {
                const loading = this.$loading({
                    lock: true,
                    text: 'Loading',
                    spinner: 'el-icon-loading',
                    background: 'rgba(0, 0, 0, 0.7)'
                });
                setTimeout(() => {
                    loading.close();
                }, 2000);
            }
        },
        data() {
            const generateData = _ => {
                const data = [];
                for (let i = 1; i <= 15; i++) {
                    data.push({
                        key: i,
                        label: `备选项 ${ i }`,
                        disabled: i % 4 === 0
                    });
                }
                return data;
            };
            return {
                show: true,
                value1: null,
                data: generateData(),
                value3: [1],
                value4: [1],
                renderFunc(h, option) {
                    return " <span>{ option.key } - { option.label }</span>";
                }
            };
        }
    }
</script>
<style>
    .transfer-footer {
        margin-left: 20px;
        padding: 6px 5px;
    }
    .transition-box {
        margin-bottom: 10px;
        width: 200px;
        height: 100px;
        border-radius: 4px;
        background-color: #409EFF;
        text-align: center;
        color: #fff;
        padding: 40px 20px;
        box-sizing: border-box;
        margin-right: 20px;
    }
</style>