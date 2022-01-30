<script lang="ts">
    import { tick } from "svelte";
    import { compute_slots, createEventDispatcher, dataset_dev, loop_guard } from "svelte/internal";
    import { onMount } from "svelte";
    import { flip } from "svelte/animate"
    import { slide } from "svelte/transition"

    // Import Swiper Svelte components
    import { Swiper, SwiperSlide } from "swiper/svelte";
    // Import Swiper styles
    import "swiper/css";
    
    //定数
    const LONG_SWIPES_RATIO = 0.15;
    const MODE_LATER = 1;
    const MODE_NEXT = 2;
    //------------プロパティ-------------------
    export let todos;
    export let mode:number;
    export let isConnecting:boolean;

    //あとで　と　今度　でタスクを切り替える
    $: shows = mode == MODE_LATER ? todos.filter((val) => !val.isnexttime) : todos.filter((val) => val.isnexttime);

    let setImageSize = (set: HTMLImageElement, vanish: HTMLImageElement, isBig: boolean) => {};
    const dispatch = createEventDispatcher();
    //時間が変更されたかをチェックする
    let todo_cp:string;
    //操作によるアニメーションの時間を管理する 
    let duration = 0;

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
        
        //スライド上のアイコンを操作する関数を返す
        return function (set: HTMLImageElement, vanish: HTMLImageElement, isBig: boolean) {
            let n = isBig ? 1.5 : 0.8;
            //スライド位置によってサイズを変更
            set.width = n * size;
            set.height = n * size;
            //逆側のアイコンをなくす
            vanish.height = 0; 
        };
    }
    //**時間が変更されていれば*/
    function onTimeUpdate(todo){
        if(todo.time && Number.isNaN(new Date(todo.time).getDate())){
			//(空白でない)不正な時間なら時間をリセットしてリターン
			todo.time = "";
            todos = todos;
			return;
		}
        setdateTodo(todo.taskno, todo.time);
        return;
    }

    //**スライドが変更されたとき*/
    async function onSlideChange(idx:number, taskNo:number){
        if(idx !== 1){ //初期化時に 0 -> 1 に移動する処理があるので無視する
            duration = 200; //個別にアニメーションの時間を管理するため
            if(idx == 0){
                //タスクトグル
                toggleTodo(taskNo);
            }else if(idx == 2){
                //タスク削除
                deleteTodo(taskNo);
            }
        }
 
        return;
    }

    //** 送信時間設定*/
    function setdateTodo(taskNo:Number, time:string){
        //親にイベントを渡す
        dispatch('setdate',{
            taskNo: taskNo,
            time: time
        });
        return;
    }

    //** タスク削除   */
    function deleteTodo(taskNo: number) {
        //親にイベントを渡す
        dispatch('delete', {
            taskNo: taskNo
        });
        return;
    }

    //** タスクトグル*/
    function toggleTodo(taskNo: number){
        //親にイベントを渡す
        dispatch('toggle', {
            taskNo: taskNo,
            mode: mode //押されたときのモードを送る
        });
        return;
    }

    //　カレンダーで値変更時10分単位で切り捨てる
    function onChange(todo){
        todo.time = todo.time ? todo.time.slice(0, -1) + '0' : "";
        todos = todos; //リアクティブにするため
        return;
    }

    //カレンダーフォーカス時
    function onFocus(todo){
        todo.time = todo.time ? todo.time : "";
        todo_cp = JSON.stringify(todo);
        return;
    }

    //カレンダーフォーカスはずれ時
    function onBlur(todo){
        todo.time = todo.time ? todo.time : "";
        //値が変更されていたらDBを更新する
        if(todo_cp !== JSON.stringify(todo)){
            onTimeUpdate(todo);
        }

        todo_cp = null;
        return;
    }

    //スライド時
    function onProgress(e){
        let elm = e.detail[0][0].el?.closest(".task_wrapper")?.firstElementChild;
        let progress = 0.5 - e.detail[0][1];
        let isBig = Math.abs(progress) > (LONG_SWIPES_RATIO / 2);
        if(progress < 0){
            elm && setImageSize(elm.lastElementChild, elm.firstElementChild, isBig);
        }else{
            elm && setImageSize(elm.firstElementChild, elm.lastElementChild, isBig);
        }
    }

    //** 時間を捻じ曲げて表示*/
    function showTime(str:string){
        const today = new Date();
        const tomorrow = new Date();
        tomorrow.setDate(today.getDate() + 1);
        let date = new Date(str);
        let ret = "";
        if(!Number.isNaN(date.getDate())){
            if(date.getDate() == today.getDate() && date.getMonth() == today.getMonth() && date.getFullYear() == today.getFullYear())
            {
                ret = "今日";
            }else if(date.getDate() == tomorrow.getDate() && date.getMonth() == tomorrow.getMonth() && date.getFullYear() == tomorrow.getFullYear()){
                ret = "明日";
            }else{ 
                //年をまたぐかどうか
                if(date.getFullYear() !== today.getFullYear()){
                    //またぐなら yyyy年m月d日
                    ret = date.getFullYear() + "年";
                }
                //またがないなら m月d日
                ret += Number(date.getMonth() + 1) + "月" + date.getDate() + "日";
            }
            //時間を足す
            ret += " " + ('0' + date.getHours()).slice(-2) + ":" + ('0' + date.getMinutes()).slice(1, 2) + '0';
        }else{
            ret = "今度";
        }
        
        return ret;
    }

</script>
        {#each shows as todo (todo.taskno)}
            <div class="task" animate:flip="{{duration: 250}}" out:slide|local="{{duration: duration}}" on:outroend="{() => duration = 0}">
                <div class="task_wrapper">
                    <div class="task_bottom">
                        <img src={mode === MODE_LATER ? "./img/btn_nexttime.png" : "./img/btn_later.png"} alt="次" class="left" width="1px" height="1px"/>
                        <img src="./img/btn_check.png" alt="完了" class="right" width="1px" height="1px"/>
                    </div>
                    <div class="task_top">
                        <Swiper
                            on:slideChange={(e) => {
                                onSlideChange(e.detail[0][0].activeIndex, todo.taskno);
                            }}
                            on:progress={onProgress}
                            on:touchStart={(e) => e.detail[0][0].el.classList.add('move')}
                            on:touchEnd={(e) => e.detail[0][0].el.classList.remove('move')}
                            initialSlide= {1}
                            longSwipesRatio={LONG_SWIPES_RATIO}
                            shortSwipes={true}
                            threshold={50}
                            >

                            <SwiperSlide class="task_delete"><span /></SwiperSlide>

                            
                            <SwiperSlide class="task_contents"> 
                                {#if mode === MODE_LATER}
                                  <span class="time">{showTime(todo.time)}</span>
                                {:else}
                                  <span class="time">
                                      <span class="edit_time" >
                                          {showTime(todo.time)}
                                          <input type="datetime-local" step="600" bind:value={todo.time} class="clearText" 
                                          on:change={()=> onChange(todo)} on:focus={()=>onFocus(todo)} on:blur={()=>onBlur(todo)}>
                                      </span>
                                  </span>
                                {/if}
                                <span class="text">{todo.task}</span>
                            </SwiperSlide>
                            

                            <SwiperSlide class="task_delete"><span /></SwiperSlide>
                        </Swiper>
                    </div>
                </div>
            </div>
        {/each}

<style lang="scss">
    $line_color: #06c755;
    /*タスク*/
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
        /*下の部分*/
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
        /*上の部分*/
        div.task_top {
            height: 100%;
            width: 100%;
        }
    }
    
    /*編集可能の時間*/
    span.edit_time{
        position:relative;
        width:auto;
        height:100%;
        display:inline-block;
        input[type="datetime-local"]{
             width:100%;
             height:100%;
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

/*文章をなくす共通クラス*/
.clearText {
	text-indent:150%;
	white-space:nowrap;
	overflow:hidden;
}
</style>
