// import java.util.ArrayList;
// import java.util.List;

// public class FlattenArray {

//     public static List<Integer> flatten(Object[] nestedArray) {
//         List<Integer> result = new ArrayList<>();
//         for (Object element : nestedArray) {
//             if (element instanceof Object[]) {
//                 // If the element is an array, recursively flatten it and add its elements to the result
//                 for (Integer num : flatten((Object[]) element)) {
//                     result.add(num);
//                 }
//             } else if (element instanceof Integer) {
//                 // If the element is an integer, add it to the result list
//                 result.add((Integer) element);
//             } else {
//                 throw new IllegalArgumentException("Array contains elements that are neither Integer nor Object[]");
//             }
//         }
//         return result;
//     }

//     public static void main(String[] args) {
//         Object[] nestedArray = {
//             1, 
//             new Object[] {2, 3, new Object[] {4, new Object[] {1,3}}}, 
//             new Object[] {6, new Object[] {7, 8}}, 
//             9
//         };
//         List<Integer> flatArray = flatten(nestedArray);
//         System.out.println(flatArray);  // 输出：[1, 2, 3, 4, 5, 6, 7, 8, 9]
//     }
// }
import java.util.*;
public class QuickSort {
    public static void quickSort(int[] arr, int low, int high) {
        if (low < high) {
            int pivotIndex = partition(arr, low, high);
            quickSort(arr, low, pivotIndex - 1);
            quickSort(arr, pivotIndex + 1, high);
        }
    }

    private static int partition(int[] arr, int low, int high) {
        int pivot = arr[high];
        int i = (low - 1);
        for (int j = low; j < high; j++) {
            if (arr[j] < pivot) {
                i++;
                // Swap arr[i] and arr[j]
                int temp = arr[i];
                arr[i] = arr[j];
                arr[j] = temp;
            }
        }
        // Swap arr[i+1] and arr[high] (or pivot)
        int temp = arr[i + 1];
        arr[i + 1] = arr[high];
        arr[high] = temp;
        
        return i + 1;
    }

    public static void main(String[] args) {
        int[] arr = {4, 3, 8, 9, 1, 5};
        quickSort(arr, 0, arr.length - 1);
        System.out.println(Arrays.toString(arr));
    }
}