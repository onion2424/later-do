<script lang="ts">
	import axios from "axios";
	import { dataset_dev } from "svelte/internal";
	// Import Swiper Svelte components
	import { Swiper, SwiperSlide } from "swiper/svelte";

	// Import Swiper styles
	import "swiper/css";
	//------------プロパティ-------------------
	let todos = [];
	let isInClient = false;
	//--------------関数----------------------

	//** タスク削除   */
	function deleteTodo(taskNo) {
		//jsonでPOSTを送ってbodyにとりにいく
		axios
			.post(
				"/delete-task",
				JSON.stringify({ id_token: idToken, taskNo: taskNo })
			)
			.then((res) => {
				//ディープコピーをする
				console.log(res.data);
				let data = JSON.parse(JSON.stringify(res.data));
				if (data.Status === "OK") {
					todos = data.Contents;
				} else {
					Promise.reject(data.message);
				}
			})
			.catch((e) => {
				//閉じる
				Promise.resolve().then(() => alert(e));
				//.then(() => window.open("about:blank", "_self").close());
			});
	}

	//-----------起動時実行-----------------------

	//ロード時にユーザ情報をサーバに送る
	window.addEventListener("load", () => {
		const myLiffId = "1656807318-km8WVpYe";
		const liff = (window as any).liff;

		//LIFFで立ち上げているかどうかの判定
		if ((isInClient = liff.isInClient())) {
			//LIFFで立ち上げた場合のメッセージ

			//LIFF初期化
			liff.init({
				liffId: myLiffId,
			}).then(() => {
				//idTokenを取得
				const idToken = liff.getIDToken();

				//jsonでPOSTを送ってbodyにとりにいく
				axios
					.post("/get-tasks", JSON.stringify({ id_token: idToken }))
					.then((res) => {
						//ディープコピーをする
						console.log(res.data);
						let data = JSON.parse(JSON.stringify(res.data));
						if (data.Status === "OK") {
							todos = data.Contents;
						} else {
							Promise.reject(data.message);
						}
					})
					.catch((e) => {
						//閉じる
						Promise.resolve().then(() => alert(e));
						//.then(() =>
						//	window.open("about:blank", "_self").close()
						//);
					});
			});
		}
	});
</script>

<main>
	ためし
	{#if isInClient}
		{#if todos}
			{#each todos as todo}
			<div class="task">
				<div class="task_bottom">
					<img src="./img/btn_check.png" alt="完了" />
				</div>
				<div class="task_top">
					<Swiper on:progress={(e) => console.log(e.detail)}
						allowSlidePrev={false}
						longSwipesRatio={0.2}
						shortSwipes={false}
						>
						<SwiperSlide class="task_contents">
							<span>{todo.nextTime}</span>
							<span>{todo.task}</span>
						</SwiperSlide>
						<SwiperSlide class="task_delete"><span></span></SwiperSlide>
					</Swiper>
				</div>
			</div>
					<!-- <p>
						{todo.task}
					</p>
					<button on:click={() => deleteTodo(todo.taskNo)} />-->
			{/each}
		{:else}
			<p>タスクを追加してね</p>
		{/if}
	{:else}
		<p>
			LINE外からのこのWEBページの利用はできません。
			<br
			/>LINEアプリ内でこのメッセージが表示されているのなら、リロードによってなおる場合があります。
		</p>
		<div class="task">
			<div class="task_bottom">
				<img src="./img/btn_check.png" alt="完了" />
			</div>
			<div class="task_top">
				<Swiper on:progress={(e) => console.log(e.detail)}
					allowSlidePrev={false}
					longSwipesRatio={0.2}
					shortSwipes={false}
					>
					<SwiperSlide class="task_contents">
						<span class="time">今日 9:00</span>
						<span>タスク</span>
					</SwiperSlide>
					<SwiperSlide class="task_delete"><span></span></SwiperSlide>
				</Swiper>
			</div>
		</div>
	{/if}
</main>

<style lang="scss">
	$line_color:#06c755;
	main {
		margin: 0 auto;
		height: 100%;
	}

	h1 {
		color: #ff3e00;
		text-transform: uppercase;
		font-size: 4em;
		font-weight: 100;
	}

	@media (min-width: 640px) {
		main {
			max-width: none;
		}
	}
	div.task {
		position:relative;
		height:calc(100% / 6);
		width: 100%;
		div.task_bottom {
			z-index:-1;
			position:absolute;
			background-color: $line_color;
			height: 100%;
			width: 100%;
			img{
				position:absolute;
				height:15%;
				right:5%;
				top:calc((100% - 15%) / 2);
			}
		}
		div.task_top{
			height:100%;
		}
	}
</style>
