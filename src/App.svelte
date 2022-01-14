<script lang="ts">
	export let name: string;

	//ロード時にユーザ情報をサーバに送る
	window.onload = () => {
		const myLiffId = "1656807318-km8WVpYe";
		const divPage = document.getElementById("liff-page");
		const liff = (window as any).liff;
		// p要素の取得
		const pElement = document.getElementById("liff-message");
		divPage.appendChild(pElement);

		//LIFFで立ち上げているかどうかの判定
		if (liff.isInClient()) {
			//LIFFで立ち上げた場合のメッセージ
			pElement.innerHTML = "これはLIFF画面です";

			//LIFF初期化
			liff.init({
				liffId: myLiffId,
			}).then(() => {
				//プロフィール情報の取得
				liff.getProfile().then((profile) => {
					const name = profile.displayName;
					const lineId = profile.userId;

					alert(lineId);
					const pElement2 = document.createElement("p");
					pElement2.innerHTML = `あなたの名前は${name}です。LINE IDは${lineId}です。`;
					divPage.appendChild(pElement2);
				});
			});
		} else {
			pElement.innerHTML = "これはLIFF画面じゃありません";
		}
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
