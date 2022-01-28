<script lang="ts">
	import axios from "axios";
	import Tasks from "./Tasks.svelte";

	//------------プロパティ-------------------
	let todos = [];
	let isInClient = false;
	let idToken;
	let waitPromise;
	let isLoadEnd = false;

	//--------------関数----------------------

	//** タスク削除   */
	function deleteTodo(e) {
		let taskNo = e.detail.taskNo;
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
				} else {
					throw data.message;
				}
			})
			.catch((e) => {
				//閉じる
				Promise.resolve()
					.then(() => alert(e));
				//	.then(() => window.open("about:blank", "_self").close());
			});
	}

	//-----------起動時実行-----------------------

	//ロード時にユーザ情報を取得
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
							isLoadEnd = true;
						} else {
							throw data.message;
						}
					})
					.catch((e) => {
						//閉じる
						Promise.resolve()
							.then(() => alert(e));
							//.then(() =>
							//	window.open("about:blank", "_self").close()
							//);
					});
			});
		}else{
			todos = [{time: "今日", task: 'あいうえお'}, {time: "明日", task: 'かきくけこ'}];
		}
	});
</script>

<main>
	{#if isInClient}
		{#await waitPromise}
			<p>...データ取得中</p>
		{:then}
			{#if todos.length > 0}
			  <Tasks todos={todos} on:delete={deleteTodo}/>
			{:else if isLoadEnd}
			<div class="nothing_task">
				<div class="background">
					<div>
						<p>あとでやることはありません。<br/>LINEではなしかけてとうろくしてね！</p>
					</div>
				</div>
			</div>	
			{/if}
		{/await}
	{:else}
		<p>
			LINE外からのこのWEBページの利用はできません。
			<br
			/>LINEアプリ内でこのメッセージが表示されているのなら、リロードによってなおる場合があります。
		</p>
		{#if todos.length > 0}
		  <Tasks todos={todos}/>
	    {/if}
	{/if}
</main>
<menu>
	メニューです
</menu>
あいうえお
<style lang="scss">
	$line_color: #06c755;
	main {
		margin: 0 auto;
		height: calc(100% - 50px);
		//marginをはみださせない
		display:flow-root;
		p{
			color: $line_color;
			text-align:center;
		}
	}
	menu{
		height: 50px;
		background-color: blue;
	}

	div.nothing_task{
		height:100%;
		width:100%;
	    div.background{
			background:url('../img/back_img.png');
   			background-size:contain;
   			height:calc(100% / 2);
			aspect-ratio: 1 / 1;
			//中央に配置
			position:absolute;
			top:0;
			left:0;
			right: 0;
			bottom:0;
			background-repeat: no-repeat;
			margin: auto;
			div{
				position:relative;
				height: 100%;
				width: 100%;
			}
			p{
				position :absolute;
				left:50%;
				bottom:0;
				width:100%;
				transform: translate(-50%, 0);
			}
		}
	}

</style>
