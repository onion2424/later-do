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
    onMount(()=>{
        setImageSize = setImageSize_enclosure();
    });
    //** 画像のサイズをクロージャで持たせておく*/
    function setImageSize_enclosure() {
        let elm = document.querySelector("img") as HTMLImageElement;
        const width = elm.width;
        const height = elm.height;
        return function (elm: HTMLImageElement, progress: number) {
            let n = progress > LONG_SWIPES_RATIO ? 2 : 1;
            elm.width = n * width;
            elm.height = n * height;
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
                        <img src="./img/btn_check.png" alt="完了" />
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
                height: 1em;
                width: 1em;
                right: 5%;
                top: calc((100% - 15%) / 2);
            }
        }
        div.task_top {
            height: 100%;
            width: calc(100% - 5px); /*少しはみ出させる*/
        }
    }
</style>
