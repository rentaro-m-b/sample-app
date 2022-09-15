<x-app-layout>
     <x-slot name="header">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
               {{ __('Show') }}
          </h2>
     </x-slot>

     <div class="py-12">
          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

               <div class="bg-white shadow-sm sm:rounded-lg mb-10">
                    <div x-data="{toggle : false}" class="p-6 flex items-center justify-between">

                         <a href="{{ route('tweets.edit', $tweet->id) }}">
                              <button class="rounded bg-green-400 py-2 px-3">編集</button>
                         </a>
                         <a href="{{ route('replies.create', $tweet->id) }}">
                              <button class="rounded bg-green-400 py-2 px-3">返信</button>
                         </a>
                         <button x-show="!toggle" @click="toggle = ! toggle" class="p-2 bg-blue-400 rounded">削除</button>
                         <div x-show="toggle" @click.away="toggle = false" class="flex items-center mx-3 justify-between" x-transition:enter="transition transform ease-out duration-300" x-transition:enter-start="-translate-x-5 opacity-0" x-transition:enter-end="translate-x-0">
                              <form method="POST" action="{{ route('tweets.destroy', $tweet->id) }}">
                                   @csrf
                                   @method('DELETE')
                                   <button type="submit" class="py-2 px-3 bg-red-400 rounded-lg">決定</button>
                              </form>

                              <button @click="toggle = false" class="p-2 mx-3 rounded-sm shadow-sm bg-yellow-100">キャンセル</button>

                              <p class="text-red-300 ">本当にいいですか？</p>
                         </div>

                    </div>
               </div>

               <div class="flex flex-col p-6 bg-white shadow-lg rounded-lg ">
                    <div class="flex my-3 py-2 items-center justify-between border-b-2">
                         <small class="text-sm text-gray-700">{{ $tweet->updated_at }}</small>
                    </div>
                    <p class="text-gray-700">{{ $tweet->user->name }}</p>
                    <p class="my-3 text-gray-700 text-sm">
                         {{ $tweet->contents }}
                    </p>
               </div>

          </div>
     </div>
</x-app-layout>