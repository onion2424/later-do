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
					<Swiper
						spaceBetween={0}
						slidesPerView={1}
						on:slideChange={() => console.log("slide change")}
						on:swiper={(e) => console.log(e.detail[0])}
					>
						<SwiperSlide class="task_contents"
							><span>{todo.task}</span></SwiperSlide
						>
						<SwiperSlide class="task_delete"><span>削除</span></SwiperSlide>
					</Swiper>
					<!-- <p>
						{todo.task}
					</p>
					<button on:click={() => deleteTodo(todo.taskNo)} />-->
				</div>
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
		<Swiper
		spaceBetween={50}
		slidesPerView={1}
		on:slideChange={() => console.log("slide change")}
		on:swiper={(e) => console.log(e.detail[0])}
		on:progress={(e) => console.log(e.detail)}
	>
		<SwiperSlide class="task_contents"
			><span>タスク</span></SwiperSlide
		>
		<SwiperSlide class="task_delete"><span><img src='./img/btn_check.png' alt = '完了'></span></SwiperSlide>
	</Swiper>
	{/if}
</main>

<style lang="scss">
	main {
		text-align: center;
		padding: 1em;
		max-width: 240px;
		margin: 0 auto;
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
	div.task{
		width:100%;
		//左端にする
		span img{
			position:absolute;
			right: 5%;
		}
	}

</style>
