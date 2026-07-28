document.addEventListener("DOMContentLoaded", function () {
    let iotPollingInterval = null;

    const connectScaleButton = document.getElementById("connectiotScaleButton");
    const disconnectScaleButton = document.getElementById("disconnectiotScaleButton");
    const scaleStatus = document.getElementById("iotscaleStatus");

    const birthHeightInput = document.getElementById("birth_height");
    const birthWeightInput = document.getElementById("birth_weight");

    if (!connectScaleButton) {
        console.error("Tombol connectScaleButton tidak ditemukan");
        return;
    }

    if (!disconnectScaleButton) {
        console.error("Tombol disconnectScaleButton tidak ditemukan");
        return;
    }

    function setScaleStatus(message, type = "default") {
        if (!scaleStatus) return;

        scaleStatus.className = "text-sm";

        if (type === "success") {
            scaleStatus.classList.add("text-teal-600");
        } else if (type === "warning") {
            scaleStatus.classList.add("text-yellow-600");
        } else if (type === "error") {
            scaleStatus.classList.add("text-red-600");
        } else {
            scaleStatus.classList.add("text-gray-500");
        }

        scaleStatus.textContent = message;
    }

    function setButtonState(isConnected) {
        if (isConnected) {
            connectScaleButton.classList.add("hidden");
            connectScaleButton.style.display = "none";

            disconnectScaleButton.classList.remove("hidden");
            disconnectScaleButton.style.display = "inline-block";
        } else {
            disconnectScaleButton.classList.add("hidden");
            disconnectScaleButton.style.display = "none";

            connectScaleButton.classList.remove("hidden");
            connectScaleButton.style.display = "inline-block";
        }
    }

    async function getLatestIotMeasurement() {
        try {
            const response = await fetch("/api/iot/measurement/latest", {
                method: "GET",
                headers: {
                    Accept: "application/json",
                },
            });

            if (!response.ok) {
                throw new Error(`HTTP Error ${response.status}`);
            }

            const result = await response.json();

            if (!result.success || !result.data) {
                setScaleStatus("Menunggu data dari perangkat IoT...", "warning");
                return;
            }

            const data = result.data;

            if (
                birthHeightInput &&
                data.birth_height !== null &&
                data.birth_height !== undefined
            ) {
                birthHeightInput.value = Number(data.birth_height).toFixed(2);
                birthHeightInput.dispatchEvent(new Event("input", { bubbles: true }));
                birthHeightInput.dispatchEvent(new Event("change", { bubbles: true }));
            }

            if (
                birthWeightInput &&
                data.birth_weight !== null &&
                data.birth_weight !== undefined
            ) {
                birthWeightInput.value = Number(data.birth_weight).toFixed(2);
                birthWeightInput.dispatchEvent(new Event("input", { bubbles: true }));
                birthWeightInput.dispatchEvent(new Event("change", { bubbles: true }));
            }

            const heightText =
                data.birth_height !== null && data.birth_height !== undefined
                    ? Number(data.birth_height).toFixed(2)
                    : "-";

            const weightText =
                data.birth_weight !== null && data.birth_weight !== undefined
                    ? Number(data.birth_weight).toFixed(2)
                    : "-";

            setScaleStatus(
                `IoT terhubung | TB: ${heightText} cm | BB: ${weightText} kg`,
                "success"
            );
        } catch (error) {
            console.error("Gagal mengambil data IoT:", error);

            setScaleStatus(
                "Gagal mengambil data IoT. Periksa ESP32, WiFi, dan server Laravel.",
                "error"
            );
        }
    }

    function connectIotScale() {
        setButtonState(true);
        setScaleStatus("Menghubungkan ke perangkat IoT melalui WiFi...", "warning");

        getLatestIotMeasurement();

        if (iotPollingInterval) {
            clearInterval(iotPollingInterval);
        }

        iotPollingInterval = setInterval(getLatestIotMeasurement, 2000);
    }

    function disconnectIotScale() {
        if (iotPollingInterval) {
            clearInterval(iotPollingInterval);
            iotPollingInterval = null;
        }

        setButtonState(false);
        setScaleStatus("Timbangan terputus", "default");
    }

    connectScaleButton.addEventListener("click", connectIotScale);
    disconnectScaleButton.addEventListener("click", disconnectIotScale);
});
