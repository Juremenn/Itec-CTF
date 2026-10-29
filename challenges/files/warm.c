#include <stdio.h>
#include <string.h>

int main(int argc, char *argv[]) {
    if (argc == 1) {
        puts("Halo pengguna! Berikan saya opsi -h untuk mempelajari apa yang bisa saya lakukan!");
        return 0;
    }

    if (strcmp(argv[1], "-h") == 0) {
        puts("Oh, butuh bantuan? Sebenarnya program ini tidak melakukan banyak hal, tetapi saya menyimpan flag ini FLAG{b1scu1ts_4nd_gr4vy_57d8c79}");
        return 0;
    }

    printf("Saya tidak tahu apa arti dari '%s'! Namun saya tahu apa fungsi dari opsi -h!\n", argv[1]);
    return 0;
}
