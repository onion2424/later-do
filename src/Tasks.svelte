<script lang="ts">
    import axios from "axios";
    import { tick } from "svelte";
    import { createEventDispatcher, dataset_dev, loop_guard } from "svelte/internal";
    import { onMount } from "svelte";

    // Import Swiper Svelte components
    import { Swiper, SwiperSlide } from "swiper/svelte";
    // Import Swiper styles
    import "swiper/css";
    //定数
    const LONG_SWIPES_RATIO = 0.15;
    const MODE_LATER = 1;
    const MODE_NEXT = 2;
    //------------プロパティ-------------------
    export let todos = [];
    export let mode;
    //あとで　と　今度　でタスクを切り替える
    $: shows = mode == MODE_LATER ? todos.filter((val) => !val.isnexttime) : todos.filter((val) => val.isnexttime);

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
            let n = progress > LONG_SWIPES_RATIO / 2 ? 1.5 : 0.8;
            elm.width = n * size;
            elm.height = n * size;
        };
    }

    function onSlideChange(idx:number, taskNo:number, time:string){
        if(idx == 0){
            //タスクトグル
            toggleTodo(taskNo, time);
        }else if(idx == 2){
            //タスク削除
            deleteTodo(taskNo);
        }
        return;
    }
    //** タスク削除   */
    function deleteTodo(taskNo: number) {
        //親にイベントを渡す
        dispatch('delete', {
            taskNo: taskNo
        });
    }
    //** タスクトグル*/
    function toggleTodo(taskNo: number, time: string){
        dispatch('toggle', {
            taskNo: taskNo,
            time:time
        });
    }

    //** 時間を捻じ曲げて表示*/
    function showTime(str:string){
        const today = new Date();
        const tomorrow = new Date();
        tomorrow.setDate(today.getDate() + 1);
        let date = new Date(str);
        let ret = "今度";
        if(!Number.isNaN(date.getDate())){
            if(date.getDate() == today.getDate() && date.getMonth() == today.getMonth() && date.getFullYear() == today.getFullYear())
            {
                ret = "今日";
            }else if(date.getDate() == tomorrow.getDate() && date.getMonth() == tomorrow.getMonth() && date.getFullYear() == tomorrow.getFullYear()){
                ret = "明日";
            }else{
                ret = date.getFullYear() + "/" + date.getMonth() + "/" + date.getDate();
            }
            ret += " " + date.getHours + ":" + date.getMinutes();
        }
        
        return ret;
    }

</script>
        {#each shows as todo (todo.taskno)}
            <div class="task">
                <div class="task_wrapper">
                    <div class="task_bottom">
                        <img src="./img/btn_next.png" alt="次" class="left" width="1px" height="1px"/>
                        <img src="./img/btn_check.png" alt="完了" class="right" width="1px" height="1px"/>
                    </div>
                    <div class="task_top">
                        <Swiper
                            on:slideChange={(e) => {
                                onSlideChange(e.detail[0][0].activeIndex, todo.taskno, todo.time);
                            }}
                            on:progress={(e) => {
                                let elm;
                                let progress = 0.5 - e.detail[0][1];
                                if(progress < 0){
                                    elm =
                                    e.detail[0][0].el?.closest(".task_wrapper")
                                        ?.firstElementChild?.lastElementChild;
                                   progress *= -1; //正にする
                                }else{
                                    elm =
                                    e.detail[0][0].el?.closest(".task_wrapper")
                                        ?.firstElementChild?.firstElementChild;
                                }
                                elm ? setImageSize(elm, progress) : false;
                            }}
                            initialSlide= {1}
                            longSwipesRatio={LONG_SWIPES_RATIO}
                            shortSwipes={false}
                        >
                            <SwiperSlide class="task_delete"
                            ><span /></SwiperSlide
                            >

                            <SwiperSlide class="task_contents">
                                {#if mode === MODE_LATER}
                                  <span class="time">{todo.time}</span>
                                {:else}
                                  <span class="time">
                                      <span class="edit_time" >
                                          {showTime(todo.time)}
                                          <input type="datetime-local" step="600" bind:value={todo.time} class="clearText" on:timeupdate={()=>console.log(todo.time)}>
                                      </span>
                                  </span>
                                {/if}
                                <span class="text">{todo.task}</span>

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
                top: 50%;
                transform: translate(-50%, -50%);
                transition: 0.1s;
                &.right{
                    left: calc(100% - 30px);
                }
                &.left{
                    left: 30px;
                }
            }
        }
        div.task_top {
            height: 100%;
            width: 100%; /*少しはみ出させる*/
        }
    }
    
    span.edit_time{
        position:relative;
        width:auto;
        height:100%;
        display:inline-block;
        input[type="datetime-local"]{
             width:100%;
             appearance: none;
             position: absolute;
             top: 0;
             left: 0;
             background-color: rgba(255, 255, 255, 0);
             padding: 0;
             margin:0;
             border:none;
        }
    }

// ::-webkit-datetime-edit {
//     display: none;
// }

// ::-webkit-calendar-picker-indicator {
//     cursor: pointer;
//     margin:0;
//     padding: 0;
// }

.clearText {
	text-indent:150%;
	white-space:nowrap;
	overflow:hidden;
}
</style>
