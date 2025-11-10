import "./bootstrap";

// 📌 ページ（HTML）が完全に表示されたら、この処理を始めます
document.addEventListener("DOMContentLoaded", function () {
    // 📌 「必須項目（required）」がついた全てのinputタグを取得します
    const inputs = document.querySelectorAll("input[required]");

    // 📌 それぞれのinputタグ（email、passwordなど）について、順番に処理していきます
    inputs.forEach((input) => {
        // 🏷 エラーメッセージ用のIDを決めます（例：email-error、password-error）
        const errorId = `${input.id}-error`;

        // 📌 該当するIDをもつエラーメッセージ要素（div）を取得します
        const errorElem = document.getElementById(errorId);

        // 📌 エラーメッセージが見つかった場合、inputに「この説明を読んでね」という印をつけます（aria-describedby）
        if (errorElem) {
            input.setAttribute("aria-describedby", errorId);
        }

        // ✏️ 入力欄に何か文字を入力した時（リアルタイム）に下の処理を行います
        input.addEventListener("input", () => {
            // ✅ HTMLに元々書かれたルール（required、minlengthなど）でチェックします
            const isValid = input.checkValidity();

            // ⚠️ 入力が正しいかどうかを、「スクリーンリーダー（読み上げツール）」などに伝えるための属性を設定します
            input.setAttribute("aria-invalid", isValid ? "false" : "true");

            // 📌 エラー用の説明文がある場合、正しい時は非表示、間違っていれば表示します
            if (errorElem) {
                errorElem.style.display = isValid ? "none" : "block";
            }
        });

        input.addEventListener("blur", () => {
            const isValid = input.checkValidity();
            input.setAttribute("aria-invalid", isValid ? "false" : "true");
            if (errorElem) {
                errorElem.style.display = isValid ? "none" : "block";
            }
        });
    });

    // 🧪 パスワード確認欄がある場合、一致チェックを追加（register用）
    const pw = document.getElementById("password");
    const pwConf = document.getElementById("password_confirmation");
    const pwConfErr = document.getElementById("password_confirmation-error");

    if (pw && pwConf && pwConfErr) {
        pwConf.setAttribute("aria-describedby", "password_confirmation-error");

        //  共通の一致判定関数の定義
        const validateMatch = () => {
            const pwVal = pw.value.trim();
            const pwConfVal = pwConf.value.trim();
            const isPwValid = pwVal.length >= 8;
            const isFilled = pwConfVal.length > 0;
            const isMatch = pwVal === pwConfVal;

            // aria-invalid の更新
            const shouldShowError = isPwValid && isFilled && !isMatch;
            pwConf.setAttribute(
                "aria-invalid", shouldShowError ? "true" : "false");

            if (!isPwValid || !isFilled) {
                // パスワードが未入力 or 確認欄が空 → エラー非表示
                pwConfErr.style.display = "none";
            } else if (!isMatch) {
                // 不一致 → エラー表示
                pwConfErr.textContent = "パスワードが一致しません";
                pwConfErr.style.display = "block";
            } else {
                // 完全一致 → エラー非表示
                pwConfErr.style.display = "none";
            }
        };
        // イベント登録
        pwConf.addEventListener("input", validateMatch);
        pw.addEventListener("input", validateMatch);
        pwConf.addEventListener("blur", validateMatch);
        pw.addEventListener("blur", validateMatch);
    }
});
