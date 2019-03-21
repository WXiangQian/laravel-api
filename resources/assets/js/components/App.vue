<template>
    <div>
        <top-menu></top-menu>
        <transition name="fade" mode="out-in">
            <router-view></router-view>
        </transition>
        <footer-menu></footer-menu>
    </div>
</template>

<script>
    import TopMenu from './common/TopMenu'
    import FooterMenu from './common/FooterMenu'
    export default {
        components:{
            TopMenu,
            FooterMenu,
        }
    }
</script>