<script lang="ts">
	import axios from "axios";
	import { tick } from "svelte";
	import Tasks from "./Tasks.svelte";
	//--------------定数-------------
	const MODE_LATER = 1;
	const MODE_NEXT = 2;
	//------------プロパティ-------------------
	let mode = MODE_LATER; //あとで　か　こんど
	let isConnecting = false; //サーバーと通信中かどうか
	let isAnimating;

	// LINE関連
	let isInClient = false;
	let idToken;

	//描画関連
	let isLoadEnd = false; //初期ロードが終わっているか
	let waitPromise; //タスク取得中かどうかを判断するPromise
	let todos = []; //表示するタスク
	$: later = JSON.parse(JSON.stringify(todos?.filter((val) => !val.isnexttime)));
	$: nexttime = JSON.parse(JSON.stringify(todos?.filter((val) => val.isnexttime)));
	let ctlIcon; //アイコンを操作するためのクロージャ

	//--------------関数----------------------
	//** 送信日付設定*/
	function setDateTodo(e) {
		let taskNo = e.detail.taskNo;
		let time = e.detail.time;
		//念のため2回見ておく（サーバチェックもある）
		if (time && Number.isNaN(new Date(time).getDate())) {
			//(空白でなく)不正な時間なら時間をリセットしてリターン
			let task = todos.find((val) => val.taskNo == taskNo);
			task.time = "";
			todos = todos; //リアクティブ用
			return;
		}
		//通信部分
		isConnecting = true;
		axios
			.post(
				"/setdate-task",
				JSON.stringify({
					id_token: idToken,
					taskNo: taskNo,
					time: time || "",
				})
			)
			.then((res) => {
				isConnecting = false;
				//ディープコピーをする
				let data = JSON.parse(JSON.stringify(res.data));
				if (data.Status === "OK") {
					//受け取ったデータを配列に入れる
					todos = data.Contents;
					//タスクの件数を再表示
					ctlIcon.setTaskAmount();
				} else {
					throw data.message;
				}
			})
			.catch((e) => {
				//閉じる
				Promise.resolve().then(() => alert(e))
					.then(() => window.open("about:blank", "_self").close());
			});
	}

	//** タスクトグル*/
	function toggleTodo(e) {
		let taskNo = e.detail.taskNo;

		//通信部分
		isConnecting = true;
		axios
			.post(
				"/toggle-task",
				JSON.stringify({ id_token: idToken, taskNo: taskNo })
			)
			.then((res) => {
				isConnecting = false;
				//ディープコピーをする
				let data = JSON.parse(JSON.stringify(res.data));
				if (data.Status === "OK") {
					//受け取ったデータを配列に入れる
					todos = data.Contents;
					//タスクの件数を再表示
					ctlIcon.setTaskAmount();
				} else {
					throw data.message;
				}
			})
			.catch((e) => {
				//閉じる
				Promise.resolve().then(() => alert(e)).
				then(() => window.open("about:blank", "_self").close());
			});			
	}

	//** タスク削除   */
	function deleteTodo(e) {
		let taskNo = e.detail.taskNo;
		//通信部分
		isConnecting = true;
		axios
			.post(
				"/delete-task",
				JSON.stringify({ id_token: idToken, taskNo: taskNo })
			)
			.then((res) => {
				isConnecting = false;
				//ディープコピーをする
				let data = JSON.parse(JSON.stringify(res.data));
				if (data.Status === "OK") {
					//受け取ったデータを配列に入れる
					todos = data.Contents;
					//タスクの件数を再表示
					ctlIcon.setTaskAmount();
				} else {
					throw data.message;
				}
			})
			.catch((e) => {
				//閉じる
				Promise.resolve().then(() => alert(e))
					.then(() => window.open("about:blank", "_self").close());
			});
	}

	//アイコン操作用のクロージャ
	function setTaskAmount_enclosure(){
		const laterIcon = document.getElementById('laterIcon') as HTMLElement;
		const nexttimeIcon = document.getElementById('nexttimeIcon') as HTMLElement;
		if(!laterIcon || !nexttimeIcon) throw 'アイコンが見つかりません。';
		//件数をセットする関数とアイコンの色を変える関数を返す
		return {
			setTaskAmount : function (){
			//$:later, $:nexttimeの再計算のタイミングとずれるのでtodosから数を取得
			laterIcon.dataset.num = todos?.filter((val) => !val.isnexttime).length + '';
			nexttimeIcon.dataset.num = todos?.filter((val) => val.isnexttime).length + '';
			},

			setIconColer : function (){
				if(mode==MODE_LATER){
					laterIcon.style.backgroundColor="#06c755";
					nexttimeIcon.style.backgroundColor="#f0f0f0";
				}else{
					laterIcon.style.backgroundColor="#f0f0f0";
					nexttimeIcon.style.backgroundColor="#06c755";
				}
			}

		}

	}

	//-----------起動時実行-----------------------

	//ロード時にユーザ情報を取得
	window.addEventListener("load", () => {
		const myLiffId = "1656807318-km8WVpYe";
		const liff = (window as any).liff;
		
		//bodyにマルチタッチ禁止イベントを追加
		document.getElementsByTagName('body')[0].addEventListener("touchstart", function(e:TouchEvent){
			if(e.touches.length >= 2){
				console.log('multi touch');
				e.stopPropagation();
			}
		}, true);

		//クロージャを持たせる
		ctlIcon = setTaskAmount_enclosure();
		ctlIcon.setIconColer(); //初期選択メニューに色を付ける

		//LIFFで立ち上げているかどうかの判定
		if ((isInClient = liff.isInClient())) {
			//LIFFで立ち上げた場合のメッセージ

			//LIFF初期化
			liff.init({
				liffId: myLiffId,
			}).then(() => {
				//認証用のidTokenを取得
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
							isLoadEnd = true;
							//タスクの件数を表示
							ctlIcon.setTaskAmount();
						} else {
							throw data.message;
						}
					})
					.catch((e) => {
						//閉じる
						Promise.resolve().then(() => alert(e))
						.then(() =>
							window.open("about:blank", "_self").close()
						);
					});
			});
		} 
		// 確認用
		// else {
		// 	todos = [
		// 		{ time: "今日", task: "あいうえお", isnexttime: false },
		// 		{ time: "明日", task: "かきくけこ", isnexttime: true },
		// 	];
		// }
	});
</script>

<main>
	{#if isInClient}
		{#await waitPromise}
			<p>...データ取得中</p>
		{:then}

			{#if mode === MODE_LATER && later.length > 0}
				<Tasks
					todos={later}
					mode={MODE_LATER}
					bind:isAnimating
					bind:isConnecting
					on:delete={deleteTodo}
					on:toggle={toggleTodo}
					on:setdate={setDateTodo}
				/>
			{:else if mode === MODE_NEXT && nexttime.length > 0}
				<Tasks
					todos={nexttime}
					mode={MODE_NEXT}
					bind:isAnimating
					bind:isConnecting
					on:delete={deleteTodo}
					on:toggle={toggleTodo}
					on:setdate={setDateTodo}
				/>

			{:else if isLoadEnd}
				<div class="nothing_task">
					<div class="background">
						<div>
							<p>
								{#if mode === MODE_LATER}
								あとでやることはありません。<br/>
								LINEではなしかけてとうろくしてね！
								{:else}
								こんどやることはありません。<br/>
								→にスワイプしてとうろくしてね！
								{/if}
							</p>
						</div>
					</div>
				</div>
			{/if}

		{/await}
	{:else}
		<p>
			LINE外からのこのWEBページの利用はできません。<br/>
			LINEアプリ内でこのメッセージが表示されているのなら、リロードによってなおる場合があります。
		</p>
		<!--確認用 {#if todos.length > 0}
			<Tasks {todos} bind:mode />
		{/if} -->
	{/if}
</main>
<menu>
	<ul>
		<li>
			<button
				on:click={async (e) => {
					if(isAnimating) return; //サーバ通信中は動かさない
					await tick();
					mode = MODE_LATER;
					ctlIcon.setIconColer(); //メニューのアイコンの色を変える
				}}><div class="img_wrapper"><div><span id="laterIcon" data-num="0"></span></div></div><span>あとで</span>
			</button>
		</li>
		<li>
			<button
				on:click={async (e) => {
					if(isAnimating) return; //サーバ通信中は動かさない
					await tick();
					mode = MODE_NEXT;
					ctlIcon.setIconColer(); //メニューのアイコンの色を変える
				}}><div class="img_wrapper"><div><span id="nexttimeIcon" data-num="0"></span></div></div><span>こんど</span>
			</button>
		</li>
	</ul>
</menu>

<style lang="scss">
	$line_color: #06c755;
	main {
		margin: 0 auto;
		height: 100%;
		//marginをはみださせない
		display: flow-root;
		p {
			color: $line_color;
			text-align: center;
		}
	}
	menu {
		height: 50px;
		background-color: white;
		margin: 0;
		padding: 0;
		position: fixed;
		z-index:100;
		bottom: 0;
		width: 100%;
		box-sizing: border-box;
		border-top: 1px solid #dbdbdb6e;
		ul {
			display: flex;
			margin: 0;
			padding: 0;
			height: 100%;
		}
		li {
			list-style: none;
			width: calc(100% / 2);
			button {
				height: 100%;
				width: 100%;
				display: inline-block;
				border: none;
				cursor: pointer;
				background-color: rgba(255, 255, 255, 0);
				margin: 0;
				padding: 0;
				span{
					display: block;
					height:30%;
					width:100%;
					text-align: center;
					font-size: 10px;
				}
				div.img_wrapper{
					position:relative;
					height:70%;
					width:100%;
					div{
						height: 80%;
						aspect-ratio: 1 / 1;
					
						//中央に配置
						position: absolute;
						//top: 0;
						left: 0;
						right: 0;
						bottom: 0;
						margin: auto;
						span{
							display: block;
							height:100%;
							width: 100%;
							
							&#nexttimeIcon{
								background: url("../img/translate_nexttime.png");
								background-size: contain;
								background-repeat: no-repeat;
							}
							&#laterIcon{
								background: url("../img/translate_later.png");
								background-size: contain;
								background-repeat: no-repeat;
							}
							/*件数をつける 通知アイコン参考 https://qiita.com/sumtrue/items/03601443f20dee4075f0 */
							&#nexttimeIcon::before, &#laterIcon::before{
								display: flex;
    							justify-content: center;
    							align-items: center;
    							position: absolute;
    							content: attr(data-num);
    							min-width: 20px;
   								height: 20px;
    							box-sizing: border-box;
    							padding: 4px;
    							font-size: 10px;
    							font-weight: bold;
    							background-color: #ef5350da;
								color: white;
    							border: 1px solid #fff;
    							border-radius: 10px;
    							top: 0;
    							right: 0;
    							transform: translate(40%, -40%);
    							z-index: 1;
							}
							&#nexttimeIcon[data-num="0"]::before, &#laterIcon[data-num="0"]::before{
									display: none;
							}
						}

					}
				}
			}
		}
	}

	div.nothing_task {
		height: 100%;
		width: 100%;
		div.background {
			background: url("../img/back_img.png");
			background-size: contain;
			height: calc(100% / 2);
			aspect-ratio: 1 / 1;
			//中央に配置
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background-repeat: no-repeat;
			margin: auto;
			div {
				position: relative;
				height: 100%;
				width: 100%;
			}
			p {
				position: absolute;
				left: 50%;
				bottom: 0;
				width: 100%;
				transform: translate(-50%, 0);
			}
		}
	}
</style>
