<x-app-layout>
     <x-slot name="header">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
               {{ __('Edit') }}
          </h2>
     </x-slot>

     <div class="py-12">
          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

               <form action="{{ route('tweets.update', $tweet->id) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="bg-white shadow-sm sm:rounded-lg mb-10">
                         <div class="p-6 flex items-center ">
                              <button type="submit" class="rounded bg-blue-400 py-2 px-3">{{ __('Submit') }}</button>
                         </div>
                    </div>
                    <div class="bg-white shadow-sm sm:rounded-lg flex flex-col items-center p-6">
                         <textarea name="contents" class="p-4 my-1 border shadow rounded w-3/4 @error('contents') border-red-400 @endif" id="" cols="30" rows="10" required>{{ old('contents', $tweet->contents) }}</textarea>

                         @error('contents')
                         <span class="text-red-400 my-3">
                              {{$message}}
                         </span>
                         @endif
                    </div>
               </form>
          </div>

     </div>
</x-app-layout>