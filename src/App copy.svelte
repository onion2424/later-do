<script lang="ts">
	import axios from "axios";
	import { tick } from "svelte";
	import { dataset_dev } from "svelte/internal";
	import { onMount } from 'svelte';
	
	// Import Swiper Svelte components
	import { Swiper, SwiperSlide } from "swiper/svelte";
	// Import Swiper styles
	import "swiper/css";
	//定数
	const LONG_SWIPES_RATIO = 0.25;

	//------------プロパティ-------------------
	let todos = [];
	let isInClient = false;
	let idToken;
	let waitPromise;
	let isLoadEnd = false;
	let setImageSize = (elm: HTMLImageElement, progress: number) => {};

	//--------------関数----------------------

	//** 画像のサイズをクロージャで持たせておく*/
	function setImageSize_enclosure() {
		let elm = document.querySelector("img") as HTMLImageElement;
		const width = elm.width;
		const height = elm.height;
		return function (elm: HTMLImageElement, progress: number) {
			let n = progress > LONG_SWIPES_RATIO ? 2 : 1;
			elm.style.width = n + "em";
			elm.style.height = n + "em";
		};
	}

	//** タスク削除   */
	function deleteTodo(taskNo: number) {
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
					//	消したやつ以外にする
					//	複数端末での同時使用を想定していないのでデータを取り直すことはしない
					todos = todos.filter((val) => !(val.taskno === taskNo));
					isLoadEnd = true;
				} else {
					throw data.message;
				}
			})
			.catch((e) => {
				//閉じる
				Promise.resolve()
					.then(() => alert(e))
					.then(() => window.open("about:blank", "_self").close());
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
				idToken = liff.getIDToken();

				//jsonでPOSTを送ってbodyにとりにいく
				waitPromise = axios
					.post("/get-tasks", JSON.stringify({ id_token: idToken }))
					.then(async (res) => {
						//ディープコピーをする
						let data = JSON.parse(JSON.stringify(res.data));
						if (data.Status === "OK") {
							//受け取ったデータを配列に入れる
							todos = data.Contents;
						} else {
							throw data.message;
						}
					})
					.catch((e) => {
						//閉じる
						Promise.resolve()
							.then(() => alert(e))
							.then(() =>
								window.open("about:blank", "_self").close()
							);
					});
			});
		}
	});
</script>

<main>
	{#if isInClient}
		{#await waitPromise}
			<p>...データ取得中</p>
		{:then}
			{#if todos}
				<div class="exist_task">
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
												e.detail[0][0].el?.closest(
													".task_wrapper"
												)?.firstElementChild
													?.firstElementChild;
											elm
												? setImageSize(
														elm,
														e.detail[0][1]
												  )
												: false;
										}}
										allowSlidePrev={false}
										longSwipesRatio={LONG_SWIPES_RATIO}
										shortSwipes={false}
									>
										<SwiperSlide class="task_contents">
											<span class="time">{todo.time}</span
											>
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
				</div>
			{:else if isLoadEnd}
				<div class="nothing_task">
					<p>あとでやろうと思ったことを追加してね！</p>
				</div>
			{/if}
		{/await}
	{:else}
		<p>
			LINE外からのこのWEBページの利用はできません。
			<br
			/>LINEアプリ内でこのメッセージが表示されているのなら、リロードによってなおる場合があります。
		</p>
		<div class="task">
			<div class="task_wrapper">
				<div class="task_bottom">
					<img src="./img/btn_check.png" alt="完了" />
				</div>
				<div class="task_top">
					<Swiper
						on:progress={(e) => {
							let elm =
								e.detail[0][0].el?.closest(".task_wrapper")
									?.firstElementChild?.firstElementChild;
							elm ? setImageSize(elm, e[0][1]) : false;
						}}
						allowSlidePrev={false}
						longSwipesRatio={0.2}
					>
						<SwiperSlide class="task_contents">
							<span class="time">今日 9:00</span>
							<span>タスク1</span>
						</SwiperSlide>
						<SwiperSlide class="task_delete"><span /></SwiperSlide>
					</Swiper>
				</div>
			</div>
		</div>
		<div class="task">
			<div class="task_wrapper">
				<div class="task_bottom">
					<img src="./img/btn_check.png" alt="完了" />
				</div>
				<div class="task_top">
					<Swiper
						on:progress={(e) => console.log(e.detail)}
						allowSlidePrev={false}
						longSwipesRatio={0.2}
					>
						<SwiperSlide class="task_contents">
							<span class="time">今日 9:00</span>
							<span>タスク2</span>
						</SwiperSlide>
						<SwiperSlide class="task_delete"><span /></SwiperSlide>
					</Swiper>
				</div>
			</div>
		</div>
	{/if}
</main>

<style lang="scss">
	$line_color: #06c755;
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
