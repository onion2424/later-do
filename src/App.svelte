<script lang="ts">
	export let name: string;
	import axios from "axios";
	import { dataset_dev } from "svelte/internal";
	//ロード時にユーザ情報をサーバに送る
	window.addEventListener('load',  () => {
		const myLiffId = "1656807318-km8WVpYe";
		const divPage = document.getElementById("liff-page");
		const liff = (window as any).liff;
		// p要素の取得
		const pElement = document.getElementById("liff-message");
		divPage.appendChild(pElement);

		//LIFFで立ち上げているかどうかの判定
		//if (liff.isInClient()) {
		//LIFFで立ち上げた場合のメッセージ
		pElement.innerHTML = "これはLIFF画面です";

		//LIFF初期化
		liff.init({
			liffId: myLiffId,
		}).then(() => {
			//idTokenを取得
			const idToken = liff.getIDToken();
			
			//jsonでPOSTを送ってbodyにとりにいく
			axios.post('/id_api.php', JSON.stringify({ id_token: idToken }))
			.then((res) => {
				//ディープコピーをする
				console.log(res.data);
				let data = JSON.parse(JSON.stringify(res.data));
				console.log(data[0]?.task || 'nothing');
			}).catch(e => {
				console.error('認証に失敗しました。');
			});
		});
		//} else {
		//	pElement.innerHTML = "これはLIFF画面じゃありません";
		//}
	});
</script>

<main>
	<div id="liff-page">
		<h1>ようこそLIFFの世界へ</h1>
		<p id="liff-message" />
	</div>
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
</style>
