<script lang="ts">
	export let name: string;
	import axios from 'axios';
import { dataset_dev } from 'svelte/internal';
	//ロード時にユーザ情報をサーバに送る
	window.onload = () => {
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
				alert(JSON.stringify(idToken));
				axios.post('/id_api.php', {id_token : idToken})
				.then(response => {
					let data = response.data;
					alert(data.name);
				}).catch(e=>alert('認証に失敗しました'));
			});
		//} else {
		//	pElement.innerHTML = "これはLIFF画面じゃありません";
		//}
	};
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
