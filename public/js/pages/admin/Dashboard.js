import ModeToggle from "../../modules/ModeToggle.js";

import RequestHandler from "../../libs/RequestHandler.js";

export default class Dashboard {
  constructor() {
    setTimeout(() => {
      const modeToggle = new ModeToggle(".mode-toggle");
    }, 100);

    this.receiptDelete();

    this.receiptOpenStatus();
  }

  receiptOpenStatus() {
    const receiptDeleteButtons = document.querySelectorAll(".receipt-button");

    receiptDeleteButtons.forEach((button) => {
      button.addEventListener("click", () => {
        this.receiptRequest(button.dataset.id);
      });
    });
  }

  receiptDelete() {
    const receiptDeleteButtons = document.querySelectorAll(
      ".receipt-delete-button"
    );

    receiptDeleteButtons.forEach((button) => {
      button.addEventListener("click", () => {
        this.receiptDeleteRequest(
          button.dataset.id,

          button.dataset.tableNumber,

          button.dataset.tableArea
        );
      });
    });
  }

  async receiptDeleteRequest(id, tableNumber, tableArea) {
    if (
      !confirm("Bileti ləğv etsəniz, bilet məlumatları tamamilə məhv ediləcək!")
    ) {
      return;
    }

    if (
      !confirm(
        "Silməyə davam etmək üçün təsdiqləyin.\nBu əməliyyat geri qaytarılmır."
      )
    ) {
      return;
    }

    const requestHandler = new RequestHandler();

    const data = [
      { key: "id", value: id },

      { key: "table-area", value: tableArea },

      { key: "table-number", value: tableNumber },
    ];

    const response = await requestHandler.post(
      baseUrl + "terminal-service/delete-receipt",

      data
    );

    if (response.status_code == "success") {
      alert("Adisyon Silindi.");

      document.getElementById("order-" + id).remove();
    } else {
      alert("Bilet silinərkən xəta baş verdi.\nƏməliyyat uğursuz oldu.");
    }
  }

  async receiptRequest(id) {
    const requestHandler = new RequestHandler();

    const data = [{ key: "id", value: id }];

    const response = await requestHandler.post(
      baseUrl + "terminal-service/single-slip-printing",

      data
    );

    if (response.status_code == "success") {
      alert("Çap sorğusu göndərildi.");
    } else {
      alert("Çap sorğusu göndərilərkən xəta baş verdi!\nƏməliyyat uğursuz oldu.");
    }
  }
}
