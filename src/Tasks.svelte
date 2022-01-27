<script lang="ts">
    import axios from "axios";
    import { tick } from "svelte";
    import { createEventDispatcher, dataset_dev } from "svelte/internal";
    import { onMount } from "svelte";

    // Import Swiper Svelte components
    import { Swiper, SwiperSlide } from "swiper/svelte";
    // Import Swiper styles
    import "swiper/css";
    //定数
    const LONG_SWIPES_RATIO = 0.25;

    //------------プロパティ-------------------
    export let todos = [];

    let setImageSize = (elm: HTMLImageElement, progress: number) => {};
    const dispatch = createEventDispatcher();
    //--------------関数----------------------

    //**マウント時*/
    onMount(async ()=>{
        await tick();
        setImageSize = setImageSize_enclosure();
    });
    //** 画像のサイズをクロージャで持たせておく*/
    function setImageSize_enclosure() {
        //基準のフォントサイズを取得
        const size = parseFloat(window.getComputedStyle(document.documentElement).getPropertyValue('font-size')) || 16;
        return function (elm: HTMLImageElement, progress: number) {
            let n = progress > LONG_SWIPES_RATIO ? 1.5 : 0.8;
            elm.width = n * size;
            elm.height = n * size;
        };
    }

    //** タスク削除   */
    function deleteTodo(taskNo: number) {
        //親にイベントを渡す
        dispatch('delete', {
            taskNo: taskNo
        });
    }
</script>
        {#each todos as todo (todo.taskno)}
            <div class="task">
                <div class="task_wrapper">
                    <div class="task_bottom">
                        <img src="./img/btn_check.png" alt="完了" width="1px" height="1px"/>
                    </div>
                    <div class="task_top">
                        <Swiper
                            on:slideChange={() => {
                                deleteTodo(todo.taskno);
                            }}
                            on:progress={(e) => {
                                let elm =
                                    e.detail[0][0].el?.closest(".task_wrapper")
                                        ?.firstElementChild?.firstElementChild;
                                elm ? setImageSize(elm, e.detail[0][1]) : false;
                            }}
                            allowSlidePrev={false}
                            longSwipesRatio={LONG_SWIPES_RATIO}
                            shortSwipes={false}
                        >
                            <SwiperSlide class="task_contents">
                                <span class="time">{todo.time}</span>
                                <span>{todo.task}</span>
                            </SwiperSlide>
                            <SwiperSlide class="task_delete"
                                ><span /></SwiperSlide
                            >
                        </Swiper>
                    </div>
                </div>
            </div>
        {/each}

<style lang="scss">
    $line_color: #06c755;

    div.task {
        height: auto;
        width: 100%;
        margin: 2vw 0;
        box-sizing: border-box;
        /* 表示部分	*/
        div.task_wrapper {
            position: relative;
            height: 100%;
            width: 100%;
        }

        div.task_bottom {
            z-index: -1;
            position: absolute;
            background-color: #bbff99;
            height: 100%;
            width: 100%;
            img {
                position: absolute;
                right: 5%;
                top: 50%;
                transform: translate(-50%, 50%);
                transition: 0.2s;
            }
        }
        div.task_top {
            height: 100%;
            width: calc(100% - 5px); /*少しはみ出させる*/
        }
    }
</style>
