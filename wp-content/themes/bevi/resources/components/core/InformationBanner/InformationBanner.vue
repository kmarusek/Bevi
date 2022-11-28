<template>

    <section class="w-full informationBanner-wrap "
    :style="colorStyles" 
    v-if="banner.show === true"
    >
     <div class="informationBanner-content">
        <p>
        {{banner.text}}
        </p>
     </div> 
     <div class="informationBanner-cta">
        <a v-if="banner.cta.url"
        :href="banner.cta.url"
        :target="banner.cta.taget ?? '_self'"
        >
          <div class="informationBanner-btn">
            <p>
            {{ banner.cta.title }}
            </p>
          </div>
        </a>
     </div>
    </section>

</template>

<script>
    export default {
        props: {
            banner: {
                required: true,
                type: Object,
            },
        },
        computed: {
            colorStyles(){
                return {
                    "background-color": this.banner.color
                };
            }
        },
        mounted () {
            //all scripts
        },
    };
</script>

<style lang="scss" scoped>

    .informationBanner-wrap {
        padding: 8px;
        display:flex;
        justify-content: space-around;
        align-items: center;
        flex-wrap: nowrap;
        margin-top: -16px;
        margin-bottom: 16px;
        height: 64px;
        font-family: Space Grotesk,sans-serif;
       .informationBanner-content {
            width: 50%;
            p{
            font-size: 16px;
            line-height: 30px;
            color: #fafafa;
            letter-spacing: 0.64px;
            margin-right: 18px;
            text-align: right;
            }
       }
        .informationBanner-cta{ 
            width: 50%;
            a .informationBanner-btn {
            border: 1px solid #fafafa;
            background-color: transparent;
            border-radius: 24px;
            padding: 11px 14px;
            display: flex;
            justify-content: center;
            align-items: center;
            max-width: 200px;
            transition: .3s all;
                p{
                font-size: 16px;
                font-weight: 700;
                line-height: 14px;
                color: #fafafa;
                transition: .3s all;
                }
            } 
            a:hover .informationBanner-btn{
                background-color: #fafafa;
                transition: .3s all;
                p{
                    color: #00223e;
                    transition: .3s all;
                }
            }
        }
    }

  @media screen and (max-width: 800px) {
    .informationBanner-wrap {
        .informationBanner-content{
            width: 60%;
            p{
            font-size: 14px;
            line-height: 24px;
            letter-spacing: .56px;
            text-align: left;
            }
        }
        .informationBanner-cta{ 
            width: 40%;
        }
    }
}
    
</style>