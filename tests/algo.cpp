#include <iostream>
#include <vector>
#include <string>
using namespace std;

class algoTrade{
public:
    vector<int> sortArray(vector<int>& nums) {
        vector<int> temp(nums.size());
        mergeSort(nums, 0, nums.size()-1, temp);
        return nums;
    }
    void mergeSort(vector<int>& nums, int left, int right, vector<int>& temp){
        if(left < right){
            int mid = (left+right)/2;
            mergeSort(nums, left, mid, temp);
            mergeSort(nums, mid+1, right, temp);
            merge(nums, left, mid, right, temp);
        }
    }
    void merge(vector<int>& nums, int left, int mid, int right, vector<int>& temp){
        copy(nums.begin()+left, nums.begin()+right+1, temp.begin()+left);
        int leftPointer = left, rightPointer = mid+1, numsPointer = left;
        while (leftPointer <= mid && rightPointer <= right){
            if(temp[leftPointer] < temp[rightPointer]){
                nums[numsPointer] = temp[leftPointer];
                leftPointer++;
            }
            else{
                nums[numsPointer] = temp[rightPointer];
                rightPointer++;
            }
            numsPointer++;
        }
        //add all the elements from whichever half was not fully used
        if(leftPointer <= mid){
            copy(temp.begin()+leftPointer, temp.begin()+mid+1, nums.begin()+numsPointer);
        }
        else{
            copy(temp.begin()+rightPointer, temp.begin()+right+1, nums.begin()+numsPointer);
        }
    }
};
